<?php

class application_frontend_explanation_model extends library_default_classes_model {

    public function getExplanation($type) {

        $query = "select explanation from explanation where type = '".$type."'";
        $queryResult = parent::query($query);
        $explanation = NULL;

        if(parent::rowExist($queryResult)) {
            $explanation = parent::queryResult($queryResult, 'explanation');
        }

        return $explanation;

    }

}


?>