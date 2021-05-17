
@extends('layouts.template')
@section('main')
@csrf
{{-- @dump($arResult) --}}
<form action="" method="post">
    {{addB24Auth(true)}}

<h3>Задачи по плану</h3>
<br>
@foreach ($arResult['PLAN'] as $arPlan)
    <strong>Задача {{$arPlan['PLAN']->titleTask}} </strong>, запланировано {{$arPlan['AR_TIME']['PLAN']['hours']}} ч  {{$arPlan['AR_TIME']['PLAN']['minute']}} м {{$arPlan['AR_TIME']['PLAN']['time']}} с 
    <br>
    Факт  {{$arPlan['AR_TIME']['FACT']['hours']}} ч  {{$arPlan['AR_TIME']['FACT']['minute']}} м {{$arPlan['AR_TIME']['FACT']['time']}} с 
    <br>
    Комментарий( факт)
    <br>

    <input  class="input_std"type="text" name="comments_fact[{{$arPlan['PLAN']->id}}]" value="{{$arPlan['PLAN']->comments_fact}}">
    <br>
    
    Время:
    <br>

    <ul>
        @foreach ($arPlan['TIME'] as $arTime)
    
            <li>{{$arTime->arTime['hours']}} ч  {{$arTime->arTime['minute']}} м {{$arTime->arTime['time']}} с -  Комментарий в задаче(Время)<input class="input_std" type="text" name="comments_time[{{$arTime->TASK_ID}}][{{$arTime->ID}}]" value="{{$arTime->COMMENT_TEXT}}"> </li>
            <input type="hidden"  name="seconds_time[{{$arTime->TASK_ID}}][{{$arTime->ID}}]" value="{{$arTime->SECONDS}}">
        @endforeach
     
    </ul>
    <hr>
@endforeach
<hr>
<hr>
<h3>Задачи без планов</h3>
<br>

@foreach ($arResult['NO_PLAN'] as $idTask => $arTasks)
    Задача <strong>{{$arTasks['TASK']->title}}</strong>
    Время: 
    <ul>
        @foreach ($arTasks['TIME'] as $arTime)
            <li>{{$arTime->arTime['hours']}} ч  {{$arTime->arTime['minute']}} м {{$arTime->arTime['time']}} с  -  Комментарий в задаче(Время) <input class="input_std" type="text" name="comments_time[{{$idTask}}][{{$arTime->ID}}]" value="{{$arTime->COMMENT_TEXT}}">  </li>
            <input type="hidden"  name="seconds_time[{{$idTask}}][{{$arTime->ID}}]" value="{{$arTime->SECONDS}}">
        @endforeach
    </ul>


@endforeach
<input type="submit" value="Отправить">
</form>
{{-- {{var_dump($arResult)}} --}}
@endsection