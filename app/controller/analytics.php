<?php

class analytics extends controller{
    
    public function global_activity($chart = 'line'){
        $res = $this->fez->se
            ->start_date(strtotime('midnight first day of this month'))
            ->end_date(time())
            ->date_group('m-d-y')
            ->sort_by(array('date'=>1))
            ->go();
        
        $chart = array();
        $data  = array();
        foreach($res as $k => $v){
            $chart['x_axis']['labels'][]= $k;
            $data['data'][]= $v;
        }
        $chart['series'][]=$data;
        
        echo json_encode($chart);
    }
    public function time_of_day($chart = 'line'){
            $res = $this->fez->se
                ->start_date(strtotime('midnight first day of this month'))
                ->end_date(time())
                ->date_group('H')
                ->sort_by(array('date'=>1))
                ->go();

            $res = $this->fix_order($res);

            $chart = array();
            $data  = array();
            foreach($res as $k => $v){
                $chart['x_axis']['labels'][]= "'".$k."'";
                $data['data'][]= $v;
            }
            $chart['series'][]=$data;

            echo json_encode($chart);
        }

    //----------------------------------------------------------
    /*
    GECKO - METER FOR THE GECKOBOARD
    GENERATE MAX TIME AND COMPLETED TIME
    */
    
    public function status(){
        
        
        
    }

    public function fix_order($res){
        foreach($res as $k => $v){
            $temp[] = $k;
        }
        asort($temp);
        foreach($temp as $k => $v){
            $fixed[$v] = $res[$v];
        }
        return $fixed;

    }
}
