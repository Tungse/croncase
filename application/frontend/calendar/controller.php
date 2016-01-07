<?php

class application_frontend_calendar_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_calendar_model();
        $this->view = new application_frontend_calendar_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];
        parent::checkLogin($this->user['login']);

    }

    public function actionController() {

        $_GET = parent::filter($_GET);
        self::handleGET();

    }

    private function handleGET() {

        if(isset($_GET['weekIndex']) && !empty($_GET['weekIndex'])) {

            $this->weekIndex = (int)$_GET['weekIndex'];
            $this->week = $this->weekIndex*7;

            $this->firstDayOfWeek = date("Y-m-d", time() - ((date("N") - ($this->week + 1))*86400));
            $this->lastDayOfWeek = date("Y-m-d", time() + ((7 + $this->week - date("N"))*86400));

            $this->view->lastWeekIndex = $this->weekIndex - 1;
            $this->view->nextWeekIndex = $this->weekIndex + 1;

        } else {

            $this->weekIndex = 0;
            $this->firstDayOfWeek = date("Y-m-d", time()-((date("N")-1)*86400));
            $this->lastDayOfWeek = date("Y-m-d", time()+((7-date("N"))*86400));
            $this->view->lastWeekIndex = -1;
            $this->view->nextWeekIndex = 1;

        }

        $this->view->event = $this->model->getEventFromWeek($this->firstDayOfWeek, $this->lastDayOfWeek);
        $this->view->dayInfo = $this->view->getDayInfo($this->weekIndex);
        $this->view->organizeEventOfWeek();

        echo $this->view->loadCalendarTemplate();

    }

}

?>