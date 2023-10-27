var twilio_video_client = null;
var isVideoChatActive = false;
video_chat_available = true;
var localTracks = [];
var localParticipantContainer;


var videochat_GridContainer = $('#video-chat-grid');
var video_chat_formData = {
    add: 'video_chat'
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

    if ($(".main .chatbox").attr('group_id') !== undefined) {
        video_chat_formData = {
            add: 'video_chat',
            group_id: $(".main .chatbox").attr('group_id')
        };
    } else if ($(".main .chatbox").attr('user_id') !== undefined) {
        video_chat_formData = {
            add: 'video_chat',
            user_id: $(".main .chatbox").attr('user_id')
        };
    } else {
        console.log('Error : Failed to fetch conversation info');
        return;
    }

    if (user_csrf_token !== null) {
        video_chat_formData['csrf_token'] = user_csrf_token;
    }

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        video_chat_formData["login_session_id"] = user_login_session_id;
        video_chat_formData["access_code"] = user_access_code;
        video_chat_formData["session_time_stamp"] = user_session_time_stamp;
    }

    if (call_notification_timeout_id) {
        clearTimeout(call_notification_timeout_id);
    }

    $('.call_notification').attr('current_call_id', 0);
    $('.call_notification').addClass('d-none');

    $('.main .middle > .video_chat_interface > .video_chat_container > .video_chat_grid').html('');
    $('.main .middle > .video_chat_interface').removeClass('d-none');

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

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_video_call_camera", function(e) {

    if (!twilio_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    localTracks.forEach(function (track) {
        if (track.kind === 'video') {
            if (track.isEnabled) {
                track.disable();
            } else {
                track.enable();
            }
        }
    });

    if ($(this).find('.cam_disabled').hasClass('d-none')) {
        $(this).find('.cam_not_disabled').addClass('d-none');
        $(this).find('.cam_disabled').removeClass('d-none');
    } else {
        $(this).find('.cam_disabled').addClass('d-none');
        $(this).find('.cam_not_disabled').removeClass('d-none');
    }

});

$("body").on("click", ".main .middle > .video_chat_interface > .video_chat_container .toggle_video_call_mic", function(e) {

    if (!twilio_video_client) {
        return;
    }

    if (!isVideoChatActive) {
        return;
    }

    localTracks.forEach(function (track) {
        if (track.kind === 'audio') {
            if (track.isEnabled) {
                track.disable();
            } else {
                track.enable();
            }
        }
    });

    if ($(this).find('.mic_muted').hasClass('d-none')) {
        $(this).find('.mic_not_muted').addClass('d-none');
        $(this).find('.mic_muted').removeClass('d-none');
    } else {
        $(this).find('.mic_muted').addClass('d-none');
        $(this).find('.mic_not_muted').removeClass('d-none');
    }

});


function exit_video_chat() {
    $('.main .middle > .video_chat_interface').addClass('d-none');
    leaveChannel();
    stop_update_video_chat_status();
}

function leaveChannel() {

    if (!isVideoChatActive) {
        return;
    }

    if (twilio_video_client) {
        twilio_video_client.disconnect();
        twilio_video_client = null;
    }
    localTracks.forEach(function (track) {
        track.stop();
    });
    localTracks = [];
    if (localParticipantContainer !== undefined && localParticipantContainer) {
        localParticipantContainer.remove();
    }

    videochat_GridContainer.innerHTML = '';

    isVideoChatActive = false;
}

function create_video_chat() {

    $.ajax({
        type: "POST",
        url: api_request_url,
        data: video_chat_formData,
        dataType: "json",
        success: function(data) {},
        error: function(jqXHR, textStatus, errorThrown) {}
    }).done(function (data) {

        if (data.alert_message !== undefined) {
            alert(data.alert_message);
            exit_video_chat();
            return;
        }

        var token = data.token;
        var roomName = data.channel;

        Twilio.Video.connect(token, {
            name: roomName,
            tracks: localTracks
        }).then(function (newRoom) {
            twilio_video_client = newRoom;
            console.log('Connected to Room: ' + twilio_video_client.name);
            update_video_chat_status();

            isVideoChatActive = true;

            if ($('.main .middle > .video_chat_interface').hasClass('d-none')) {
                exit_video_chat();
                return;
            }


            Twilio.Video.createLocalTracks().then(function (tracks) {
                localTracks = tracks;

                localParticipantContainer = $('<div></div>').addClass('participant-container');
                const localVideoElement = tracks.find(track => track.kind === 'video').attach();
                localParticipantContainer.append(localVideoElement);
                localParticipantContainer.addClass('identity').addClass('You');
                localParticipantContainer.append('<span class="participant_name">You</span>');

                twilio_video_client.localParticipant.publishTracks(localTracks);

                videochat_GridContainer.append(localParticipantContainer);
                arrange_video_chat_grid();

                twilio_video_client.on('trackSubscribed', function (track, publication, participant) {
                    console.log(participant);

                    if ($('.participant-container[user_sid="'+participant.sid+'"]').length > 0) {
                        var mediaElement = track.attach();
                        $('.participant-container[user_sid="'+participant.sid+'"]').append(mediaElement);
                    } else {
                        var participantContainer = $('<div></div>').addClass('participant-container').attr("user_sid", participant.sid);

                        var mediaElement = track.attach();
                        participantContainer.append(mediaElement);
                        participantContainer.append('<span class="participant_name">@'+participant.identity+'</span>');

                        videochat_GridContainer.append(participantContainer);
                        arrange_video_chat_grid();
                    }

                    twilio_video_client.on('participantDisconnected', function (disconnectedParticipant) {
                        console.log('Participant ' + disconnectedParticipant.identity + ' has disconnected');
                        $('.participant-container').each(function () {
                            if ($(this).attr("user_sid") !== undefined && $(this).attr("user_sid") === disconnectedParticipant.sid) {
                                $(this).remove();
                            }
                        });
                    });
                });
            }).catch(function (error) {
                exit_video_chat();
                console.error('Error accessing local media:', error);
            });
        }).catch(function (error) {
            exit_video_chat();
            console.error('Error connecting to Room:', error);
        });
    })
    .fail(function (error) {
        exit_video_chat();
        console.error('Error fetching token:', error);
    });

}