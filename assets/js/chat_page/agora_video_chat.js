var agora_video_client = null;
var isVideoChatActive = false;
video_chat_available = true;


var videochat_GridContainer = document.getElementById('video-chat-grid');
var audiochat_GridContainer = document.getElementById('audio-chat-grid');
var agora_remoteStreams = {};
var video_chat_formData = new FormData();


var localTracks = {
    videoTrack: null,
    audioTrack: null
};
var remoteUsers = {};
var options = {
    appid: null,
    channel: null,
    uid: null,
    token: null
};

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_chat_window", function(e) {
    $('.main .middle > .video_chat_interface').toggleClass('show_chat_window');
});

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .leave_video_call", function(e) {
    exit_video_chat();
});

$("body").on('mouseenter', '.main .middle > .video_chat_interface', function(e) {
    $(".main .middle>.video_chat_interface>.video_chat_container>.icons").fadeIn();
});

$("body").on('mouseleave', '.main .middle > .video_chat_interface', function(e) {
    $(".main .middle>.video_chat_interface>.video_chat_container>.icons").fadeOut();
});

$("body").on("click", ".main .chatbox .join_video_call", function(e) {

    video_chat_formData = new FormData();
    video_chat_formData.append('add', 'video_chat');

    if ($(".main .chatbox").attr('group_id') !== undefined) {
        video_chat_formData.append('group_id', $(".main .chatbox").attr('group_id'));
    } else if ($(".main .chatbox").attr('user_id') !== undefined) {
        video_chat_formData.append('user_id', $(".main .chatbox").attr('user_id'));
    } else {
        console.log('Error : Failed to fetch conversation info');
        return;
    }

    if (user_csrf_token !== null) {
        video_chat_formData.append('csrf_token', user_csrf_token);
    }

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        video_chat_formData.append('login_session_id', user_login_session_id);
        video_chat_formData.append('access_code', user_access_code);
        video_chat_formData.append('session_time_stamp', user_session_time_stamp);
    }

    $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').html('');
    $('.main .middle > .video_chat_interface').removeClass('d-none');

    if (call_notification_timeout_id) {
        clearTimeout(call_notification_timeout_id);
    }

    $('.call_notification').attr('current_call_id', 0);
    $('.call_notification').addClass('d-none');

    if (isVideoChatActive) {
        exit_video_chat();
    } else {

        if ($('.main .chatbox').attr('user_id') !== undefined) {
            current_video_caller_id = $('.main .chatbox').attr('user_id');
        }

        initilazing_video_chat();
        create_video_chat();
    }
});


$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_video_call_mic", function(e) {

    if (!agora_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    if ($(this).find('.mic_muted').hasClass('d-none')) {

        localTracks.audioTrack.setMuted(true);
        $(this).find('.mic_not_muted').addClass('d-none');
        $(this).find('.mic_muted').removeClass('d-none');

    } else {
        localTracks.audioTrack.setMuted(false);
        $(this).find('.mic_muted').addClass('d-none');
        $(this).find('.mic_not_muted').removeClass('d-none');

    }

});


$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_video_call_camera", function(e) {

    if (!agora_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    if ($(this).find('.cam_disabled').hasClass('d-none')) {

        if (localTracks.videoTrack) {
            localTracks.videoTrack.setEnabled(false);
        }

        $(this).find('.cam_not_disabled').addClass('d-none');
        $(this).find('.cam_disabled').removeClass('d-none');
    } else {

        if (localTracks.videoTrack) {
            localTracks.videoTrack.setEnabled(true);
        }

        $(this).find('.cam_disabled').addClass('d-none');
        $(this).find('.cam_not_disabled').removeClass('d-none');
    }

});


agora_video_client = AgoraRTC.createClient({
    mode: "rtc", codec: "vp8"
});
agora_video_client.on("user-published", handleUserPublished);
agora_video_client.on("user-unpublished", handleUserUnpublished);

var create_video_chat = async () => {
    try {

        if (isVideoChatActive) {
            console.log('Video chat is already active. Leaving current session.');
            leaveChannel();
        }

        await joinChannel();
        console.log('AgoraRTC client initialized');
    } catch (error) {
        console.log(error);
    }
};

var exit_video_chat = async () => {
    $('.main .middle > .video_chat_interface').addClass('d-none');
    leaveChannel();
    stop_update_video_chat_status();
};

async function joinChannel() {

    var response = await fetch(api_request_url, {
        method: 'POST',
        body: video_chat_formData,
    });

    if (!response.ok) {
        throw new Error('Failed to fetch token from the server');
    }

    var data = await response.json();

    if (!data || typeof data !== 'object') {
        exit_video_chat();
        console.log('Invalid JSON data');
        return;
    } else if (data.alert_message !== undefined) {
        alert(data.alert_message);
        exit_video_chat();
        return;
    } else if (!data.channel) {
        exit_video_chat();
        console.log('Channel property is missing in JSON data');
        return;
    }

    options = {
        appid: data.app_id,
        channel: data.channel,
        uid: null,
        token: data.token
    };

    [options.uid, localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
        agora_video_client.join(options.appid, options.channel, options.token || null, data.uid),
        AgoraRTC.createMicrophoneAudioTrack(),
        AgoraRTC.createCameraVideoTrack()
    ]);

    isVideoChatActive = true;

    if ($('.main .middle > .video_chat_interface').hasClass('d-none')) {
        exit_video_chat();
        return;
    }

    var localVideoContainer = document.getElementById('video-chat-grid');
    var localVideoElement = document.createElement('div');
    localVideoElement.className = 'video-window';

    var participantName = document.createElement('span');
    participantName.className = 'participant_name';
    participantName.textContent = 'You';
    localVideoElement.appendChild(participantName);

    localVideoContainer.appendChild(localVideoElement);
    localTracks.videoTrack.play(localVideoElement);
    await agora_video_client.publish(Object.values(localTracks));
    console.log("publish success");
    arrange_video_chat_grid();
    update_video_chat_status();
}

async function leaveChannel() {
    if (!agora_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    for (trackName in localTracks) {
        var track = localTracks[trackName];
        if (track) {
            track.stop();
            track.close();
            localTracks[trackName] = undefined;
        }
    }

    remoteUsers = {};
    await agora_video_client.leave();

    var agoraVideoContainer = document.getElementById('video-chat-grid');
    agoraVideoContainer.innerHTML = '';

    isVideoChatActive = false;

    console.log("client leaves channel success");
}

async function videoChat_channel_subscribe(user, mediaType) {
    const uid = user.uid;
    var unique_id_hash = unicodeHash(uid);
    await agora_video_client.subscribe(user, mediaType);
    if (mediaType === 'video') {
        const player = $(`<div class="video-window player" id="player-${unique_id_hash}"><span class="participant_name">@${uid}</span></div>`);

        $("#video-chat-grid").append(player);
        user.videoTrack.play(`player-${unique_id_hash}`);
    }
    if (mediaType === 'audio') {
        user.audioTrack.play();
    }

    arrange_video_chat_grid();
}

function handleUserPublished(user, mediaType) {
    const id = user.uid;
    remoteUsers[id] = user;
    videoChat_channel_subscribe(user, mediaType);
}

function handleUserUnpublished(user, mediaType) {
    const id = user.uid;
    var unique_id_hash = unicodeHash(id);

    if (mediaType === 'video') {
        delete remoteUsers[id];
        $(`#player-${unique_id_hash}`).remove();
    }
}