function checkInput(id) {
    var input = $(id).val();
    if(input === '') {
        return false;
    } else {
        return true;
    }
}
function limitText(input, count, limit) {
    if (input.value.length > limit) {
        input.value = input.value.substring(0, limit);
    } else {
        count.value = limit - input.value.length;
    }
}
function hideSystemStatus(id) {
    $('#system_status_holder').fadeOut("fast", function() {
        $('#system_status_holder #' + id).fadeOut("fast", function() {
            $('.commentStatus').fadeIn("fast")});
    })
}
function showSystemStatus(id) {
    $('.commentStatus').fadeOut("fast", function() {
        $('.notificationStatus').fadeOut("fast", function() {
            $('#system_status_holder').fadeIn("fast"), $('#system_status_holder #' + id).fadeIn("fast")
        })})
}
function showNotification() {
    $('.commentStatus').fadeOut("fast", function() {
        $('.notificationStatus').fadeIn("fast")})
}
function hideNotification() {
    $('.notificationStatus').fadeOut("fast", function() {
        $('.commentStatus').fadeIn("fast")})
}
function deleteComment(cid) {
    $.post("comment", {deleteComment: true, cid: cid }, function() {
        tb_remove(), $("div#comment" + cid).fadeOut("slow", function() {
            $("div#comment" + cid).remove()}), showSystemStatus('comment_delete'), $("div#status" + cid).remove()
        });
}
function deleteEventComment(ecid) {
    $.post("comment?event", { deleteEventComment: true, ecid: ecid }, function() {
        tb_remove(), showSystemStatus('event_comment_delete')});
}
function deleteSubComment(csid) {
    $.post("comment", {deleteSubComment: true, csid: csid }, function() {
        tb_remove(), $("div#subComment" + csid).fadeOut("slow", function() {
            $("div#subComment" + csid).remove()}), showSystemStatus('comment_delete')
    });
}
function deletePrivatemsgGet(pid) {
    $.post("privatemsg", { deletePrivatemsg: true, pid: pid }, function() {
        tb_remove(), $('.privatemsg .pm' + pid).fadeOut('slow', function() {
            $('.privatemsg .pm' + pid).remove()
        })});
}
function deletePrivatemsgSended(psid) {
    $.post("privatemsg", { deletePrivatemsgSended: true, psid: psid }, function() {
        tb_remove(), $('.privatemsg .pm' + psid).fadeOut('slow', function() {
            $('.privatemsg .pm' + psid).remove()
        })});
}
function saveEventComment(eid) {
    var input = $(".commentEvent #event_comment_input #comment").val();
    if(input === '') {
        return false;
    } else {
        $.post("comment?event", {saveEventComment: true, eid: eid, comment: $(".commentEvent #event_comment_input #comment").val()}, function() {
            showSystemStatus('comment_event'), $.post('comment?eid=' + eid + '&event', {showEventComment: true}, function(html) {
                $('.eventView #event_comment .commentEvent #event_comment_outer #event_comment_inner').fadeOut('fast', function() {
                    $('.commentEvent #event_comment_input #comment').val(''),
                    $('.eventView #event_comment .commentEvent #event_comment_outer #event_comment_inner').prepend(html),
                    $('.eventView #event_comment .commentEvent #event_comment_outer #event_comment_inner').fadeIn('fast')
                })
            })});
        return true;
    }
}
function checkEventInput() {
    var name = $('.eventCreate #name').val();
    var dateFrom = $('.eventCreate #dateFrom').val();
    var error = false;

    if(name === '') {
        error = true;
        $('.eventCreate #name_input').css("border-color", "#FF0000");
    }

    if(dateFrom === '') {
        error = true;
        $('.eventCreate .jdpicker_w').css('border-color', '#FF0000');
    }

    if(error) {        
        return false;
    } else {
        tb_remove();
        return true;
    }
}
function deleteEvent(ueid) {
    $.post("event", { deleteEvent: true, ueid: ueid }, function() {
        tb_remove(), $("div#event" + ueid).fadeOut("slow", function() {
            $("div#event" + ueid).remove()}), showSystemStatus('event_delete')
    });
}
function inviteFriendToEvent(ueid, eid) {
    $.post("event", {sendInvite: true, ueid: ueid, eid: eid, email: $(".eventInvite #email").val(), friends: $(".eventInvite #friends").val()}, function() {
        tb_remove(), showSystemStatus('event_invite')
    })
}
function rejectEventViewInvitation(iid) {
    $.post("event", {reject: true, iid: iid }, function() {
        $(".eventView #confirm_submit").fadeOut("slow", function() {
            $(".eventView #reject_submit").fadeOut("slow"),
            $(".eventView #confirm_submit").remove(),
            $(".eventView #reject_submit").remove()})
        });
}
function rejectEventInvitation(iid) {
    $.post("event", {reject: true, iid: iid }, function() {
        $(".eventShowInvitation #invitation" + iid).fadeOut("slow", function() {
            $(".eventShowInvitation #invitation" + iid).remove()})
        });
}
function addFriend(uid) {
    $.post("friend", {request: true, uid: uid}, function() {
        tb_remove(), showSystemStatus('request_friend'), $('.home #hidden_links #add_friend_link').remove()});
}
function sendFriendInvitation() {
    $.post("friend", {friendInvite: true, email: $(".friendInvite #email").val(), content: $(".friendInvite #content_text").val()}, function() {
        tb_remove(), showSystemStatus('friend_invite')});
}
function checkFriendInviteInput(id) {
    var input = $(id).val();
    if(input === '') {
        $('.friendInvite #invite_email').css("border-color", "#FF0000");
        return false;
    } else {
        sendFriendInvitation();
        return true;
    }
}
function sendPrivateMsg(uid, feedback) {
    $.post("privatemsg", {privatemsgWrite: true, uid: uid, feedback: feedback, subject: $(".privatemsgWrite #subject").val(), content: $(".privatemsgWrite #privatemsg").val()}, function() {
        tb_remove(), showSystemStatus('write_privatemsg')});
}
function checkPrivatemsgInput(uid, feedback) {

    var privatemsg = $('.privatemsgWrite #privatemsg').val();
    var error = true;

    if(privatemsg === '') {
        error = false;
        $('.privatemsgWrite #privatemsg_textarea').css('border-color', '#FF0000');
    }

    if(error == true) {
        sendPrivateMsg(uid, feedback);
        return error;
    } else {
        return error;
    }

}
function checkLoginInput() {

    var email = $('.login #email_input #login_email').val();
    var password = $('.login #password_input #password').val();
    error = true;

    if(email == '' || email == 'your email address here') {
        $('.login #email_input').css('border-color', '#FF0000');
        showSystemStatus('empty_input');
        error = false;
    }
    if(password == '') {
        $('.login #password_input').css('border-color', '#FF0000');
        showSystemStatus('empty_input');
        error = false;
    }
    
    return error;
}
function checkRegisterInput() {

    var firstname = $('.register #register_firstname #firstname').val();
    var lastname = $('.register #register_lastname #lastname').val();
    var email = $('.register #register_email #email').val();
    var password = $('.register #register_password #password').val();
    error = true;

    if(firstname == '' || firstname == 'your firstname') {
        $('.register #register_firstname').css('border-color', '#FF0000');
        showSystemStatus('empty_input');
        error = false;
    }
    if(lastname == '' || lastname == 'your lastname') {
        $('.register #register_lastname').css('border-color', '#FF0000');
        showSystemStatus('empty_input');
        error = false;
    }
    if(email == '' || email == 'your email address here') {
        $('.register #register_email').css('border-color', '#FF0000');
        showSystemStatus('empty_input');
        error = false;
    }
    if(password == '') {
        $('.register #register_password').css('border-color', '#FF0000');
        showSystemStatus('empty_input');
        error = false;
    }

    return error;
}
function sendPrivateMsgAnswer(i, div_id, reader, pid) {
    $.post("privatemsg", {privatemsgAnswer: true, reader: reader, pid: pid, content: $(".privatemsg #" + div_id + " #answer" + i + " #privatemsg").val()}, function() {
        $('.privatemsg #' + div_id + ' #answer' + i).fadeOut('fast', function() {
            $('.privatemsg #' + div_id + ' #privatemsg_sended' + i).fadeIn('fast')
        })
    });
}
function checkPrivatemsgAnwserInput(i, div_id, reader, pid) {

    var privatemsg = $('.privatemsg #' + div_id + ' #answer' + i + ' #privatemsg').val();
    var error = true;

    if(privatemsg === '') {
        error = false;
        $('.privatemsg #' + div_id + ' #answer' + i).css('border-color', '#FF0000');
    }

    if(error == true) {
        sendPrivateMsgAnswer(i, div_id, reader, pid);
        return error;
    } else {
        return error;
    }

}
function checkAccountEditInput() {

    var firstname = $('.profileAccount #firstname').val();
    var lastname = $('.profileAccount #lastname').val();
    var email = $('.profileAccount #email').val();
    var password = $('.profileAccount #password').val();
    error = false;

    if(firstname == '') {
        $('.profileAccount #td_firstname').css('border-color', '#FF0000');
        error = true;
    }
    if(lastname == '') {
        $('.profileAccount #td_lastname').css('border-color', '#FF0000');
        error = true;
    }
    if(email == '') {
        $('.profileAccount #td_email').css('border-color', '#FF0000');
        error = true;
    }

    if(error === false) {
        sendAccountEdit(firstname, lastname, email, password);
    }

    return error;
}
function sendAccountEdit(firstname, lastname, email, password) {
    $.post("profile", {accountChange: true, 
                        calendar: $(".profileAccount #calendar").val(),
                        status: $(".profileAccount #status").val(),
                        comment: $(".profileAccount #comment").val(),
                        friend: $(".profileAccount #friend").val(),
                        commentPage: $(".profileAccount input:radio[name=commentPage]:checked").val(),
                        commentSub: $(".profileAccount input:radio[name=commentSub]:checked").val(),
                        eventInvite: $(".profileAccount input:radio[name=eventInvite]:checked").val(),
                        eventComment: $(".profileAccount input:radio[name=eventComment]:checked").val(),
                        eventRequest: $(".profileAccount input:radio[name=eventRequest]:checked").val(),
                        eventUpdate: $(".profileAccount input:radio[name=eventUpdate]:checked").val(),
                        privateMessage: $(".profileAccount input:radio[name=privateMessage]:checked").val(),
                        friendRequest: $(".profileAccount input:radio[name=friendRequest]:checked").val(),
                        firstname: firstname,
                        lastname: lastname,
                        email: email,
                        password: password}, function() {
        tb_remove(), showSystemStatus('update_account')});
}
function showEventCreated() {
    $('.eventAll #event_all_attending').fadeOut('fast', function() {
        $('.eventAll #joined_events').css('color','#1587B7'),
        $('.eventAll #created_events').css('color','#FFCC00'),
        $('.eventAll #event_all_created').fadeIn('fast')
    })
}
function showEventAttending() {
    $('.eventAll #event_all_created').fadeOut('fast', function() {
        $('.eventAll #created_events').css('color','#1587B7'), 
        $('.eventAll #joined_events').css('color','#FFCC00'),
        $('.eventAll #event_all_attending').fadeIn('fast')
    })
}
function nextEvent(class_id, id) {

    var count = $(class_id).length;

    for(i = 0, a = 1; i < count - 1; i++, a++) {
        if($(id + i).css('display') != 'none') {
            $(id + i).fadeOut('fast', function() {
                $(id + a).fadeIn('fast')});
            break;
        }
    }

}
function previousEvent(class_id, id) {

    var count = $(class_id).length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($(id + i).css('display') != 'none') {
            $(id + i).fadeOut('fast', function() {
                $(id + a).fadeIn('fast')});
            break;
        }
    }

}
function nextInviteEvent() {
    
    var count = $('.eventShowInvitation #event_invitation_holder .event_invite_class').length;

    for(i = 0, a = 1; i < count - 1 ; i++, a++) {
        if($('.eventShowInvitation #event_invite' + i).css('display') != 'none') {
            $('.eventShowInvitation #event_invite' + i).fadeOut('fast', function() {
                $('.eventShowInvitation #event_invite' + a).fadeIn('fast')});
            break;
        }
    }
    
}
function previousInviteEvent() {

    var count = $('.eventShowInvitation #event_invitation_holder .event_invite_class').length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($('.eventShowInvitation #event_invite' + i).css('display') != 'none') {
            $('.eventShowInvitation #event_invite' + i).fadeOut('fast', function() {
                $('.eventShowInvitation #event_invite' + a).fadeIn('fast')});
            break;
        }
    }

}
function nextEventSearch() {

    var count = $('.searchResult #event_search .event_class').length;

    for(i = 0, a = 1; i < count - 1 ; i++, a++) {
        if($('.searchResult #event' + i).css('display') != 'none') {
            $('.searchResult #event' + i).fadeOut('fast', function() {
                $('.searchResult #event' + a).fadeIn('fast')});
            break;
        }
    }
}
function previousEventSearch() {
    
    var count = $('.searchResult #event_search .event_class').length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($('.searchResult #event' + i).css('display') != 'none') {
            $('.searchResult #event' + i).fadeOut('fast', function() {
                $('.searchResult #event' + a).fadeIn('fast')});
            break;
        }
    }
}
function nextUserSearch() {

    var count = $('.searchResult #user_search .user_class').length;

    for(i = 0, a = 1; i < count - 1 ; i++, a++) {
        if($('.searchResult #user' + i).css('display') != 'none') {
            $('.searchResult #user' + i).fadeOut('fast', function() {
                $('.searchResult #user' + a).fadeIn('fast')});
            break;
        }
    }
}
function previousUserSearch() {

    var count = $('.searchResult #user_search .user_class').length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($('.searchResult #user' + i).css('display') != 'none') {
            $('.searchResult #user' + i).fadeOut('fast', function() {
                $('.searchResult #user' + a).fadeIn('fast')});
            break;
        }
    }
}
function nextUnreadEventComment() {
    
    var count = $('.eventUnreadComment #unread_comment_holder .unread_comment_class').length;

    for(i = 0, a = 1; i < count - 1 ; i++, a++) {
        if($('.eventUnreadComment #unreadComment' + i).css('display') != 'none') {
            $('.eventUnreadComment #unreadComment' + i).fadeOut('fast', function() {
                $('.eventUnreadComment #unreadComment' + a).fadeIn('fast')});
            break;
        }
    }
}
function previousUnreadEventComment() {

    var count = $('.eventUnreadComment #unread_comment_holder .unread_comment_class').length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($('.eventUnreadComment #unreadComment' + i).css('display') != 'none') {
            $('.eventUnreadComment #unreadComment' + i).fadeOut('fast', function() {
                $('.eventUnreadComment #unreadComment' + a).fadeIn('fast')});
            break;
        }
    }
}
function showEventComment() {
    $('.eventView #event_main').fadeOut('fast', function() {
        $('.eventView #event_comment').fadeIn('fast')});
}
function hideEventComment() {
    $('.eventView #event_comment').fadeOut('fast', function() {
        $('.eventView #event_main').fadeIn('fast')});
}
function showEventMap(address, name, date) {
    $.post('event', {showEventMap: true, address: address, name: name, date: date}, function(html) { $('.eventView #event_main').fadeOut('fast', function() {
        $('.eventView #event_map').fadeIn('fast'), $('.eventView #event_map #event_map_content').append(html)})})
}
function hideEventMap() {
    $('.eventView #event_map').fadeOut('fast', function() {
        $('.eventView #event_map #map').remove(), $('.eventView #event_main').fadeIn('fast')});
}
function showUnreadPrivatemsg() {

    var readed_display = $('.privatemsg #readed_message').css('display');
    var sended_display = $('.privatemsg #sended_message').css('display');

    if(readed_display == 'block') {
        $('.privatemsg #readed_message').fadeOut('fast', function() {
            $('.privatemsg #unread_message').fadeIn('fast')});
    } else if(sended_display == 'block') {
        $('.privatemsg #sended_message').fadeOut('fast', function() {
            $('.privatemsg #unread_message').fadeIn('fast')});
    }

}
function showReadedPrivatemsg() {

    var unreaded_display = $('.privatemsg #unread_message').css('display');
    var sended_display = $('.privatemsg #sended_message').css('display');

    if(unreaded_display == 'block') {
        $('.privatemsg #unread_message').fadeOut('fast', function() {
            $('.privatemsg #readed_message').fadeIn('fast')});
    } else if(sended_display == 'block') {
        $('.privatemsg #sended_message').fadeOut('fast', function() {
            $('.privatemsg #readed_message').fadeIn('fast')});
    }

}
function showSendedPrivatemsg() {

    var readed_display = $('.privatemsg #readed_message').css('display');
    var unread_display = $('.privatemsg #unread_message').css('display');

    if(readed_display == 'block') {
        $('.privatemsg #readed_message').fadeOut('fast', function() {
            $('.privatemsg #sended_message').fadeIn('fast')});
    } else if(unread_display == 'block') {
        $('.privatemsg #unread_message').fadeOut('fast', function() {
            $('.privatemsg #sended_message').fadeIn('fast')});
    }

}
function previousUnreadPrivatemsg() {

    var count = $('.privatemsg .privatemsg_unread_class').length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($('.privatemsg #unread' + i).css('display') != 'none') {
            $('.privatemsg #unread' + i).fadeOut('fast', function() {
                $('.privatemsg #unread' + a).fadeIn('fast')});
            break;
        }
    }
}
function nextUnreadPrivatemsg() {

    var count = $('.privatemsg .privatemsg_unread_class').length;

    for(i = 0, a = 1; i < count - 1 ; i++, a++) {
        if($('.privatemsg #unread' + i).css('display') != 'none') {
            $('.privatemsg #unread' + i).fadeOut('fast', function() {
                $('.privatemsg #unread' + a).fadeIn('fast'),
                    readedPrivatemsg($(".privatemsg #unread" + a + " #pid").val())});
            break;
        }
    }
}
function previousEventRequest() {

    var count = $('.eventInvitationRequest .event_request_class').length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($('.eventInvitationRequest #request' + i).css('display') != 'none') {
            $('.eventInvitationRequest #request' + i).fadeOut('fast', function() {
                $('.eventInvitationRequest #request' + a).fadeIn('fast')});
            break;
        }
    }
}
function nextEventRequest() {

    var count = $('.eventInvitationRequest .event_request_class').length;

    for(i = 0, a = 1; i < count - 1 ; i++, a++) {
        if($('.eventInvitationRequest #request' + i).css('display') != 'none') {
            $('.eventInvitationRequest #request' + i).fadeOut('fast', function() {
                $('.eventInvitationRequest #request' + a).fadeIn('fast')});
            break;
        }
    }
}
function readedPrivatemsg(pid) {
    $.post("privatemsg", {privatemsgRead: true, pid: pid});
}
function previousReadedPrivatemsg() {

    var count = $('.privatemsg .privatemsg_readed_class').length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($('.privatemsg #readed' + i).css('display') != 'none') {
            $('.privatemsg #readed' + i).fadeOut('fast', function() {
                $('.privatemsg #readed' + a).fadeIn('fast')});
            break;
        }
    }
}
function nextReadedPrivatemsg() {

    var count = $('.privatemsg .privatemsg_readed_class').length;

    for(i = 0, a = 1; i < count - 1 ; i++, a++) {
        if($('.privatemsg #readed' + i).css('display') != 'none') {
            $('.privatemsg #readed' + i).fadeOut('fast', function() {
                $('.privatemsg #readed' + a).fadeIn('fast')});
            break;
        }
    }
}
function previousSendedPrivatemsg() {

    var count = $('.privatemsg .privatemsg_sended_class').length;

    for(i = 0; i < count ; i++) {
        a = i - 1;
        if(i == 0) {
            a = 0;
        }
        if($('.privatemsg #sended' + i).css('display') != 'none') {
            $('.privatemsg #sended' + i).fadeOut('fast', function() {
                $('.privatemsg #sended' + a).fadeIn('fast')});
            break;
        }
    }
}
function nextSendedPrivatemsg() {

    var count = $('.privatemsg .privatemsg_sended_class').length;

    for(i = 0, a = 1; i < count - 1 ; i++, a++) {
        if($('.privatemsg #sended' + i).css('display') != 'none') {
            $('.privatemsg #sended' + i).fadeOut('fast', function() {
                $('.privatemsg #sended' + a).fadeIn('fast')});
            break;
        }
    }
}
function checkInputEventInviteUser(receiver) {

    var ueid = $('.eventInviteUser #ueid').val();
    var eid = $('.eventInviteUser #eid').val();

    if(ueid != '' && eid != '') {
        $.post("event", {inviteUser: true, receiver: receiver, ueid: ueid, eid: eid }, function() {
            tb_remove(), showSystemStatus('event_invite_user')});
        return true;
    } else {
        $('.eventInviteUser #event_user_name').css('border-color', '#FF0000');
        return false;
    }
}
function checkNewPasswordInput() {

    var email = $('.password #email').val();

    if(email != '') {
        $.post("password", {newPassword: true, email: email }, function() {
            tb_remove(), showSystemStatus('new_password_sended')});
        return true;
    } else {
        $('.password #new_passord').css('border-color', '#FF0000');
        return false;
    }
}
function deleteSystemText(id) {
    
    var input = $('#'+id).val();
    
    if(id == 'login_email' && input == 'your email address here') {
        document.getElementById(id).value = '';
        $('.login #' + id).css('color','#000');
    }
    if(id == 'firstname' && input == 'your firstname') {
        document.getElementById(id).value = '';
        $('.register #' + id).css('color','#000');
    }
    if(id == 'lastname' && input == 'your lastname') {
        document.getElementById(id).value = '';
        $('.register #' + id).css('color','#000');
    }
    if(id == 'email' && input == 'your email address here') {
        document.getElementById(id).value = '';
        $('.register #' + id).css('color','#000');
    }

}
function enableSystemText(id) {

    var input = $('#'+id).val();

    if(id == 'login_email' && input == '') {
        document.getElementById(id).value = 'your email address here';
        $('.login #' + id).css('color','#AAA');
    }
    if(id == 'firstname' && input == '') {
        document.getElementById(id).value = 'your firstname';
        $('.register #' + id).css('color','#AAA');
    }
    if(id == 'lastname' && input == '') {
        document.getElementById(id).value = 'your lastname';
        $('.register #' + id).css('color','#AAA');
    }
    if(id == 'email' && input == '') {
        document.getElementById(id).value = 'your email address here';
        $('.register #' + id).css('color','#AAA');
    }

}
function denyFriendRequest(uid) {
    $.post("friend", {deny: true, uid: uid }, function() {
        $('.friendAll .friendRequest' + uid).fadeOut('fast', function() {
            $('.friendAll .deny_status_message' + uid).fadeIn('fast')
        })});
}
function confirmFriendRequest(uid) {
    $.post('friend', {confirm: true, uid: uid}, function() {
        $('.friendAll .friendRequest' + uid).fadeOut('fast', function() {
            $('.friendAll .confirm_status_message' + uid).fadeIn('fast')
        })
    })
}
function showAccountSetting() {
    $('.profileAccount #notification_holder').fadeOut('fast', function() {
        $('.profileAccount #account_holder').fadeIn('fast');
    });
}
function showNotificationSetting() {
    $('.profileAccount #account_holder').fadeOut('fast', function() {
        $('.profileAccount #notification_holder').fadeIn('fast');
    });
}
function showCommentMore(lastComment) {
    $.post('comment', {showCommentMore: true, lastComment: lastComment}, function(html) {
        $('.comment  #show_comment_more').fadeOut('fast', function() {
            $('.comment  #show_comment_more').remove(), $('.comment #comment_holder').append(html);
        });
    });
}
function showWeek(weekIndex, uid) {
    $.get('calendar?uid=' + uid, {weekIndex: weekIndex}, function(html) {
    	$('#main_content .panes span .calendar').fadeOut('fast', function() {
    		$('#main_content .panes span .calendar').remove(), $('#main_content .panes span').append(html);
    	})
    })

}
function requestEventInvitation(eid, ueid) {
    $.post('event', {requestEventInvitation: true, eid: eid, ueid: ueid}, function() {
        $('.eventView #attending_status #need_invitation').fadeOut('fast', function() {
            $('.eventView #attending_status #need_invitation').remove(), 
            $('.eventView #attending_status').append('<small id="joined"> - request sended - </small>'),
            $('.eventView #update_join_button').fadeOut('fast')
        })
    })
}
function denyEventInvitationRequest(id) {
    $.post('event', {denyEventInvitationRequest: true, id: id}, function() {
        $('.eventInvitationRequest #event_request' + id).fadeOut('fast')
    })
}
function acceptEventInvitationRequest(id, eid, ueid, request) {
    $.post('event', {acceptEventInvitationRequest: true, id: id, eid: eid, ueid: ueid, request: request}, function() {
        $('.eventInvitationRequest #event_request' + id).fadeOut('fast', function() {
            $('.eventInvitationRequest #status_notification' + id).fadeIn('fast')
        })
    })
}
function joinEvent(eid) {

    var attend = $('.eventView #event_radio_option input[name=attend]:checked').val();
    var dateFrom = $('.eventView #event_date input').val();
    var timeFrom = $('.eventView #timeFrom').val();
    var timeTo = $('.eventView #timeTo').val();
    var privacy = $('.eventView #event_privacy select option:selected').val();

    $.post('event?eid=' + eid, {join: true, attend: attend, dateFrom: dateFrom, timeFrom: timeFrom, timeTo: timeTo, privacy: privacy}, function() {
        tb_remove(), showSystemStatus('event_attending')
    })
}
function joinEventInvitation(eid, uid, iid) {

    var attend = $('.eventShowInvitation #attend').val();
    var dateFrom = $('.eventShowInvitation #invitation' + iid + ' #event_info input').val();
    var timeFrom = $('.eventShowInvitation #invitation' + iid + ' #timeFrom').val();
    var timeTo = $('.eventShowInvitation #invitation' + iid + ' #timeTo').val();
    var privacy = $('.eventShowInvitation #privacy').val();

    if(dateFrom != '') {
        $.post('event?eid=' + eid, {join: true, attend: attend, dateFrom: dateFrom, timeFrom: timeFrom, timeTo: timeTo, privacy: privacy}, function() {
            $('.eventShowInvitation #invitation' + iid).fadeOut('fast', function() {
                $('.eventShowInvitation #invitation' + iid).remove()
            }),
            $.get('calendar?uid=' + uid, {weekIndex: 0}, function(html) {
                $('#main_content .panes span .calendar').fadeOut('fast', function() {
                    $('#main_content .panes span .calendar').remove(), $('#main_content .panes span').append(html);
                })
            });
        });
        return true;
    } else {
        $('.eventShowInvitation #invitation' + iid + ' .jdpicker_w').css('border-color', '#F00');
        return false;
    }
}
function confirmEventInvitation(eid, uid, iid) {

    var attend = $('.eventView #event_radio_option input[name=attend]:checked').val();
    var dateFrom = $('.eventView #event_date input').val();
    var timeFrom = $('.eventView #timeFrom').val();
    var timeTo = $('.eventView #timeTo').val();
    var privacy = $('.eventView #event_privacy select option:selected').val();

    if(dateFrom != '') {
        $.post('event?eid=' + eid, {join: true, attend: attend, dateFrom: dateFrom, timeFrom: timeFrom, timeTo: timeTo, privacy: privacy}, function() {
            tb_remove(),
            $.get('calendar?uid=' + uid, {weekIndex: 0}, function(html) {
                $('#main_content .panes span .calendar').fadeOut('fast', function() {
                    $('#main_content .panes span .calendar').remove(), $('#main_content .panes span').append(html);
                })
            });
        });
        return true;
    } else {
        $('.eventView #event_date .jdpicker_w').css('border-color', '#F00');
        return false;
    }
}
function updateEventHome(eid, uid, ueid) {

    var attend = $('.eventView #event_radio_option input[name=attend]:checked').val();
    var dateFrom = $('.eventView #event_date input').val();
    var timeFrom = $('.eventView #timeFrom').val();
    var timeTo = $('.eventView #timeTo').val();
    var privacy = $('.eventView #event_privacy select option:selected').val();

    if(dateFrom != '') {
        $.post('event?eid=' + eid + '&ueid=' + ueid, {updateEvent: true, attend: attend, dateFrom: dateFrom, timeFrom: timeFrom, timeTo: timeTo, privacy: privacy}, function() {
            tb_remove(),
            $.get('calendar?uid=' + uid, {weekIndex: 0}, function(html) {
                $('#main_content .panes span .calendar').fadeOut('fast', function() {
                    $('#main_content .panes span .calendar').remove(), $('#main_content .panes span').append(html);
                })
            });
        });
        return true;
    } else {
        $('.eventView #event_date .jdpicker_w').css('border-color', '#F00');
        return false;
    }
}
function duplicateEvent(eid, uid, ueid) {

    var attend = $('.eventView #event_radio_option input[name=attend]:checked').val();
    var dateFrom = $('.eventView #event_date input').val();
    var timeFrom = $('.eventView #timeFrom').val();
    var timeTo = $('.eventView #timeTo').val();
    var privacy = $('.eventView #event_privacy select option:selected').val();

    if(dateFrom != '') {
        $.post('event?eid=' + eid + '&ueid=' + ueid, {duplicate: true, attend: attend, dateFrom: dateFrom, timeFrom: timeFrom, timeTo: timeTo, privacy: privacy}, function() {
            tb_remove(),
            $.get('calendar?uid=' + uid, {weekIndex: 0}, function(html) {
                $('#main_content .panes span .calendar').fadeOut('fast', function() {
                    $('#main_content .panes span .calendar').remove(), $('#main_content .panes span').append(html);
                })
            });
        });
        return true;
    } else {
        $('.eventView #event_date .jdpicker_w').css('border-color', '#F00');
        return false;
    }
}
function updateEvent(eid) {

    var attend = $('.eventView #event_radio_option input[name=attend]:checked').val();
    var dateFrom = $('.eventView #event_date input').val();
    var timeFrom = $('.eventView #timeFrom').val();
    var timeTo = $('.eventView #timeTo').val();
    var privacy = $('.eventView #event_privacy select option:selected').val();

    if(dateFrom != '') {
        $.post('event?eid=' + eid, {update: true, attend: attend, dateFrom: dateFrom, timeFrom: timeFrom, timeTo: timeTo, privacy: privacy}, function() {
            tb_remove()
        });
        return true;
    } else {
        $('.eventView #event_date .jdpicker_w').css('border-color', '#F00');
        return false;
    }
}
function showCreateAccount() {
    $('.viewEvent #event_holder #create_account_button').fadeOut('fast', function() {
        $('.viewEvent #event_holder #register').fadeIn('fast')
    });
}