<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskTime; 
use App\Models\AdminTable; 
use App\Models\AuthB24 ;

class TaskTimeController extends Controller
{
    public function __construct(){
        $this->B24 = AuthB24::getInstance();
        $this->currentUserB24 = $this->B24->callMethod('user.current')->result;

    }
    private function isAdmin(){
        $arUser = AdminTable::where('id_user_b24',$this->currentUserB24->ID)->get()->values()->all();
        if(!empty($arUser)){
            return true;
        }
        else{
            return false;
        }
        
    }
    public function formatedDate($date){
        $date = explode('.',$date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
    private function getArrayTime($allTime)
    {
        $allHours =floor($allTime/3600);
        $allTime = $allTime - $allHours * 3600;
        $allMinute = floor($allTime/60);
        $allTime = $allTime - $allMinute * 60;
        return [
            'hours'=>(int) $allHours,
            'minute'=>(int) $allMinute,
            'time'=>(int) $allTime,
        ];
    }
    public function addPlan(Request $request)
    {
        $arTasks = TaskTime::get();
        // $_REQUEST["DATE"]  = date('');
        if(isset($_REQUEST["DATE"])){
            $_REQUEST["DATE"] = $this->formatedDate($_REQUEST["DATE"]);
            $arTasks = $arTasks->where('date',$_REQUEST["DATE"]);
        }
        else{
            $arTasks = $arTasks->where('date',date('Y-m-d'));

        }
        $arUsers = $this->B24->callMethod('user.get',['FILTER'=>['ACTIVE'=>'Y']])->result;
        $selectedUser = $this->currentUserB24->ID;
        if(isset($_REQUEST["USER_ID"])){
            $selectedUser = $_REQUEST["USER_ID"];
        }
        $arTasks = $arTasks->where('assigned_id',$selectedUser);

        $arTasks = $arTasks->values()->all();
        $allTime = 0;
        $allHours = 0;
        $allMinute = 0;
        foreach ($arTasks as $arTask ) {
            $allTime += $arTask->timestamp_plan;
            $arResult[$arTask->id] = $this->getArrayTime($arTask->timestamp_plan);
        }



        $arAllTime = $this->getArrayTime($allTime);
        $arResult['allTime'] = $arAllTime['time'];
        $arResult['allHours'] = $arAllTime['hours'];
        $arResult['allMinute'] =$arAllTime['minute'];
        return view('addplan',['isAdmin'=>$this->isAdmin(),'arUsers'=>$arUsers,'selectedUser'=>$selectedUser,'arTasks'=> $arTasks,'arResult'=>$arResult]);
    }

    public function index(Request $request)
    {

        $arTasks = TaskTime::where('date',date('Y-m-d'))->where('assigned_id',$this->currentUserB24->ID)->get()->values()->all();
        foreach ($arTasks as $keyTask => $arTask) {
            $arTasksTimeB24 = $this->B24->callMethod('task.elapseditem.getlist',['TASKID'=> $arTask->taskIdB24,['ID'=>"ASC"],['USER_ID'=>$this->currentUserB24->ID,'>=CREATED_DATE'=>date('Y-m-d')]])->result;
            $allTime = 0;
            foreach ($arTasksTimeB24 as $keyTimeB24 => $arTimeTask) {
                $allTime += $arTimeTask->SECONDS;
            }
            if($arTask->timestamp_fact != $allTime){
                $arTask->update(['timestamp_fact'=>$allTime]);
            }
        }
  
        // return view('index',['arTasks'=>$arTasks,'isAdmin'=>$this->isAdmin()]);
        return view('index',['isAdmin'=>$this->isAdmin()]);
    }
    public function addPlanSave(Request $request){
        
        $arTask = $request->input('task');
        unset($arTask[0]);
        // $this->B24 = AuthB24::getInstance();
        $this->currentUserB24 = $this->B24->callMethod('user.current')->result; 
        foreach ($arTask as $key => $value) {
            if(empty($request->input('task_id')[$key])){
                continue;
            }
            $arTaskB24 = $this->B24->callMethod('tasks.task.get',['taskId'=>$request->input('task_id')[$key]])->result->task;
            $allTime = ($request->input('hours_plan')[$key] * 3600) + ($request->input('minute_plan')[$key] * 60)  + $request->input('time_plan')[$key];
            $taskTime = new TaskTime;
            $taskTime->taskIdB24 = $request->input('task_id')[$key];
            $taskTime->titleTask = $request->input('task')[$key];
            $taskTime->timestamp_plan =  $allTime ;
            $taskTime->comments_plan = $request->input('comments_plan')[$key];
            $taskTime->comments_fact = "";
            // Создатель
            $taskTime->id_user = $this->currentUserB24->ID;
            $taskTime->name_user = $this->currentUserB24->LAST_NAME . ' ' . $this->currentUserB24->NAME;
            // Ответственный за задачу
            if(empty($request->input('USER_ID'))){
                $responsbleUser = $this->B24->callMethod('user.get',['ID'=>$this->currentUserB24->ID])->result[0];
            }
            else{
                $responsbleUser = $this->B24->callMethod('user.get',['ID'=>$request->input('USER_ID')])->result[0];
            }
            $taskTime->assigned_id = $responsbleUser->ID;
            $taskTime->assigned_name =  $responsbleUser->LAST_NAME . ' ' . $responsbleUser->NAME;
            
            $taskTime->timestamp_fact = 0;
            $taskTime->date = $this->formatedDate($request->input('date'));
            $taskTime->created_at = now();
            $taskTime->updated_at = now();
            $taskTime->completed = false;
            $taskTime->comments = "";
            $taskTime->save();
        }
        return redirect('/'.addB24Auth());

    }
    
    public  function getPlan(Request $request,$preset = null)
    {

        $arUsers = $this->B24->callMethod('user.get',['FILTER'=>['ACTIVE'=>'Y']])->result;
        $selectedUser = $this->currentUserB24->ID;
        $arTasks = TaskTime::get();
        $_REQUEST['date'] = date('Y-m-d');
        if(!empty($request->input('date'))){
            $_REQUEST['date'] = $this->formatedDate($request->input('date'));
        }  
        $arTasks =  $arTasks->where('date',$_REQUEST['date']);
        if(!empty($request->input('user_id'))){
            $arTasks =  $arTasks->where('assigned_id',$request->input('user_id'));
            $selectedUser = $request->input('user_id');
            // $_REQUEST['date'] = $this->formatedDate($request->input('date'));
        } 
        else{
            $arTasks =  $arTasks->where('assigned_id', $selectedUser);
            // $selectedUser = $request->input('user_id');
        }
        
        $arTasks = $arTasks->values()->all();
        $arResult = [];
        $arResult['plan_time'] =0;
        $arResult['fact_time'] =0;
        $arResult['message'] ='';
        $allTime = [
            'plan'=>0,
            'fact'=>0,
        ];
        foreach ($arTasks as $arTask ) {
            $allTime['plan']+=$arTask->timestamp_plan;
            $allTime['fact']+=$arTask->timestamp_fact;
            $arResult[$arTask->id]['plan'] =  $this->getArrayTime($arTask->timestamp_plan);
            $arResult[$arTask->id]['fact'] =  $this->getArrayTime($arTask->timestamp_fact);
            $arResult['plan_time'] += $arTask->timestamp_plan;
            $arResult['fact_time'] += $arTask->timestamp_fact;
        }
        $arResult['ALL_TIME']  = [
            'PLAN'=>$this->getArrayTime($allTime['plan']),
            "FACT"=>$this->getArrayTime($allTime['fact'])
        ];
        $arResult['total_time_left'] =  $arResult['plan_time'] - $arResult['fact_time'];
        
        if($arResult['total_time_left'] <0){
            $time = abs($arResult['total_time_left']);
            
            $arResult['total_time_left'] = $this->getArrayTime($time);
            
            $arResult['message'] = 'Вы перетрудились на :';
        // dd($arResult);
        } else {
            $arResult['total_time_left'] = $this->getArrayTime($arResult['total_time_left']);
            $arResult['message'] = 'Осталось времени на задачи :';
        }        
        // dd($arResult);
        return view('getplan',['arTasks'=>$arTasks,'isAdmin'=>$this->isAdmin(),'arUsers'=>$arUsers,'selectedUser'=>$selectedUser,'arResult'=>$arResult]);

    }
    public function getPlanToday(Request $request)
    {
        $arTasks = TaskTime::where('date',date('Y-m-d'))->all();
        var_dump($arTasks);
    }
    public function reports(Request $request){
        $this->currentUserB24;
        #TODO Добавить выволд всего плана.

        
        $arResult = [
            'PLAN'=>[],
            'NO_PLAN'=>[]
        ];
        $arTasksTimeB24 = $this->B24->callMethod('task.elapseditem.getlist',[['ID'=>"ASC"],['USER_ID'=>$this->currentUserB24->ID,'>=CREATED_DATE'=>date('Y-m-d')]])->result;
        $arIDTasks = [];
        foreach ($arTasksTimeB24 as $keyTimeB24 => $arTimeTask) {
            // var_dump($arTimeTask);
            // exit;
            $arTimeTask->arTime = $this->getArrayTime($arTimeTask->SECONDS);
            if(isset($arResult['PLAN'][$arTimeTask->TASK_ID])){
                $arResult['PLAN'][$arTimeTask->TASK_ID]['TIME'][] =$arTimeTask;

            }
            else if(isset($arResult['NO_PLAN'][$arTimeTask->TASK_ID])){
                $arResult['NO_PLAN'][$arTimeTask->TASK_ID]['TIME'][] =$arTimeTask;

            }
            else{
                $arTasks = TaskTime::where('taskIdB24',$arTimeTask->TASK_ID)->where('date',date('Y-m-d'))->where('assigned_id',$this->currentUserB24->ID)->get()->values()->all();
                if(empty($arTasks)){
                    $arResult['NO_PLAN'][$arTimeTask->TASK_ID]['TIME'][] =$arTimeTask;
                    $arResult['NO_PLAN'][$arTimeTask->TASK_ID]['TASK'] =  $this->B24->callMethod('tasks.task.get',['taskId'=>$arTimeTask->TASK_ID])->result->task;
                    

                }
                else{
                    $arIDTasks[] =$arTasks[0]->id;
                    $arResult['PLAN'][$arTimeTask->TASK_ID]['PLAN'] = $arTasks[0];
                    $arResult['PLAN'][$arTimeTask->TASK_ID]['AR_TIME']['PLAN'] = $this->getArrayTime($arTasks[0]->timestamp_plan);
                    $arResult['PLAN'][$arTimeTask->TASK_ID]['AR_TIME']['FACT'] = $this->getArrayTime($arTasks[0]->timestamp_fact);
                    $arResult['PLAN'][$arTimeTask->TASK_ID]['TASK'] =  $this->B24->callMethod('tasks.task.get',['taskId'=>$arTimeTask->TASK_ID])->result->task;
                    $arResult['PLAN'][$arTimeTask->TASK_ID]['TIME'][] =$arTimeTask;
                }

            }
        }
        $arTasks = TaskTime::where([['date',date('Y-m-d')],['assigned_id',$this->currentUserB24->ID]]);
        foreach ($arIDTasks as $idTask) {
            $arTasks = $arTasks->where('id','!=',$idTask);
        }
        $arTasks = $arTasks->get()->values()->all();
        dump($arTasks);
        dump($arIDTasks);
        return view('reports',['arResult'=>$arResult]);
    }
    public function reportsSave(Request $request)
    {
        $arComments = $request->input('comments_fact');
 
        $arTask = new TaskTime;
        if(!empty($arComments )){

            foreach ($arComments as $id => $arComment) {
                if(empty($arComment)){
                    continue;
                }
                $arTask->where('id',$id)->update(['comments_fact'=>$arComment]);
            }
        }
        $arTaskTime = $request->input('comments_time');
        $arTaskTimeSeconds = $request->input('seconds_time');
        foreach ($arTaskTime as $idTask => $arTimesTask) {
            foreach ($arTimesTask as $idTime => $arComment) {
                if(empty($arComment)){
                    continue;
                }
                $res = $this->B24->callMethod('task.elapseditem.update',['TASKID'=>$idTask,'ITEMID'=>$idTime,'ARFIELDS'=>['COMMENT_TEXT'=>$arComment,'SECONDS'=>$arTaskTimeSeconds[$idTask][$idTime]]]);

            }
        }

        return redirect('/'.addB24Auth());
    }
    public function reportsDays(Request $request){
        $arTasks = TaskTime::where([['date',date('Y-m-d')],['assigned_id',$this->currentUserB24->ID]]);
        $arResult = [];
        $arResult['TASKS'] = [];
        $arTasks = $arTasks->get()->values()->all();
        $allTimePlan = 0;
        $allTimeFact = 0;
        foreach ($arTasks as $key => &$arTask) {
            $allTimePlan +=$arTask->timestamp_plan;
            $allTimeFact +=$arTask->timestamp_fact;

            $arTask->timestamp_plan = $this->getArrayTime($arTask->timestamp_plan);
            $arTask->timestamp_fact = $this->getArrayTime($arTask->timestamp_fact);
            $arResult['TASKS'][] = $arTask;
        }
        $arResult['TIME'] = [
            'FACT'=>$this->getArrayTime($allTimeFact),
            'PLAN'=>$this->getArrayTime($allTimePlan),
        ];

        return view('reports_day',['arTasks'=>$arTasks,'arResult'=>$arResult]);
    }
    public function reportsDaysSave(Request $request)
    {
        $arTask = new TaskTime;
        $arComments = $request->input('comments');
        $arCompleted = $request->input('completed');
        foreach ($arComments as $idTask => $comment) {
            if(!empty($comment)){
                $arTask->where('id',$idTask)->update(['comments'=>$comment]);
            }
            if(isset($arCompleted[$idTask])){
                $arTask->where('id',$idTask)->update(['completed'=>true]);
            }
            else{
                $arTask->where('id',$idTask)->update(['completed'=>false]);

            }
        }
        return redirect('task/reportsDays'.addB24Auth());

        // comments
    }
    public function deleteTask($id)
    {
        $task = TaskTime::find($id);
        $task->delete();
        return redirect('/task/getPlan/'.addB24Auth());
    }

}
