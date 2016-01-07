<?php

function initialize($name) {

    switch($name) {

        case 'activation':
            $object = new application_frontend_activation_controller();
            break;
        case 'album':
            $object = new application_frontend_album_controller();
            break;
        case 'blog':
            $object = new application_frontend_blog_controller();
            break;
        case 'calendar':
            $object = new application_frontend_calendar_controller();
            break;
        case 'comment':
            $object = new application_frontend_comment_controller();
            break;
        case 'content':
            $object = new application_frontend_content_controller();
            break;
        case 'event':
            $object = new application_frontend_event_controller();
            break;
        case 'explanation':
            $object = new application_frontend_explanation_controller();
            break;
        case 'friend':
            $object = new application_frontend_friend_controller();
            break;
        case 'home':
            $object = new application_frontend_home_controller();
            break;
        case 'index':
            $object = new application_frontend_index_controller();
            break;
        case 'login':
            $object = new application_frontend_login_controller();
            break;
        case 'logout':
            $object = new application_frontend_logout_controller();
            break;
        case 'news':
            $object = new application_frontend_news_controller();
            break;
        case 'password':
            $object = new application_frontend_password_controller();
            break;
        case 'privatemsg':
            $object = new application_frontend_privatemsg_controller();
            break;
        case 'profile':
            $object = new application_frontend_profile_controller();
            break;
        case 'register':
            $object = new application_frontend_register_controller();
            break;
        case 'search':
            $object = new application_frontend_search_controller();
            break;
        case 'user':
            $object = new application_frontend_user_controller();
            break;
        case 'view':
            $object = new application_frontend_view_controller();
            break;
        default:
            $object = new application_frontend_index_controller();
            break;

    }

    return $object;

}

?>