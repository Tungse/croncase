<?php

class application_frontend_comment_view extends library_default_classes_view {

    public function loadCommentTemplate(){

        ob_start();

            include('public/templates/default/html/comment.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadCommentMoreTemplate(){

        ob_start();

            include('public/templates/default/html/commentMore.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadCommentSubTemplate(){

        ob_start();

            include('public/templates/default/html/commentSub.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadCommentDeleteTemplate(){

        ob_start();

            include('public/templates/default/html/commentDelete.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadCommentDeleteSubTemplate(){

        ob_start();

            include('public/templates/default/html/commentDeleteSub.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadCommentStatusTemplate(){

        if($this->notificationExist) {
            $this->notificationDisplay = 'block';
            $this->commentStatusDisplay = 'none';
        } else {
            $this->notificationDisplay = 'none';
            $this->commentStatusDisplay = 'block';
        }

        ob_start();

            include('public/templates/default/html/commentStatus.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadCommentEventTemplate(){

        ob_start();

            include('public/templates/default/html/commentEvent.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadCommentEventNewTemplate(){

        ob_start();

            include('public/templates/default/html/commentEventNew.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadCommentDeleteEventTemplate(){

        ob_start();

            include('public/templates/default/html/commentDeleteEvent.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>