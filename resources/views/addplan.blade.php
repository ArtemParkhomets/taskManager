@extends('layouts.template')
@section('main')
@csrf
{{-- @dump($arResult) --}}

ТАБЛИЦА ДОБАВЛЕНИЕ ПЛАНОВ
<button onclick = "addRow()">Добавить строчку</button>
<form action="" method="POST">
    {{ csrf_field() }}
    {{addB24Auth(true)}}
    @if ($isAdmin)
        <select name="USER_ID" id="USER_ID" onchange="changeField()">
        @foreach ($arUsers as $arUser)
            @if ($arUser->ID == $selectedUser)
                <option  selected value="{{$arUser->ID}}">{{$arUser->LAST_NAME.' ' . $arUser->NAME}}</option>
                
            @else
                <option   value="{{$arUser->ID}}">{{$arUser->LAST_NAME.' ' . $arUser->NAME}}</option>
                
            @endif
        @endforeach


        </select>
        
    @endif
    <style>
        .read {
            background: green;
        }
    </style>
    <input type="text" name="date" class="datepicker-here" id="DATE" data-auto-close="true" data-date="{{isset($_REQUEST['DATE'])?$_REQUEST["DATE"]:""}}">
    <br>
    <span>На сегодня запланировано {{ $arResult['allHours'] }} ч {{ $arResult['allMinute'] }} м {{ $arResult['allTime'] }} секунд</span>
    <div class="table">
        <div class="table__row row table__head">
            <div class="row__cell cell">Задача</div>
            <div class="row__cell cell">Время план</div>
            <div class="row__cell cell">Комментарий</div>
            <div class="row__cell cell">Действия</div>
        </div>
        <div id="row_example" class="table__row row " hidden>
            <div class="row__cell cell">
                <input type="text" name="task[]" class="taskSearch" >
                <input type="hidden" name="task_id[]" class="taskId">
            </div>
            <div class="row__cell cell">
                <div class="cell__time">

                    <input type="text" name="hours_plan[]"  value="1" class="input_std"> ч
                    <input type="text" name="minute_plan[]"  value="00" class="input_std"> м
                    <input type="text" name="time_plan[]"  value="00" class="input_std"> c 
                </div>
            </div>
            <div class="row__cell cell"><textarea class="textareacss" name="comments_plan[]" id="" cols="30" rows="3"></textarea></div>
            <div class="row__cell cell">#</div>
        </div>

        <div class="table__row row ">
            <div class="row__cell cell">
                <input type="text" name="task[]"  class="taskSearch" >
                <input type="hidden" name="task_id[]" class="taskId">

            </div>
            <div class="row__cell cell">
                
                <div class="cell__time">

                    <input type="text" name="hours_plan[]"  value="1" class="input_std"> ч
                    <input type="text" name="minute_plan[]"  value="00" class="input_std"> м
                    <input type="text" name="time_plan[]"  value="00" class="input_std"> c 
                </div>
            
            </div>
            <div class="row__cell cell"><textarea class="textareacss" name="comments_plan[]" id="test" cols="30" rows="3"></textarea></div>
            <div class="row__cell cell">#</div>
        </div>


    </div>
    @foreach ($arTasks as $arTask)
    <div class="table__row row read">
    
        <div class="row__cell cell">
            <input type="text" name="task_read[]"  class="taskSearch read" readonly value="{{$arTask->titleTask}}">
            <input type="hidden" name="task_id__read[]" class="taskId" value="{{$arTask->taskIdB24}}">

        </div>
 
        
        <div class="row__cell cell">
                         
            <div class="cell__time">

                <input type="text" name="hours_plan_read[]"  value="{{$arResult[$arTask->id]['hours']}}" class="input_std" readonly> ч
                <input type="text" name="minute_plan_read[]"  value="{{$arResult[$arTask->id]['minute']}}" class="input_std" readonly> м
                <input type="text" name="time_plan_read[]"  value="{{$arResult[$arTask->id]['time']}}" class="input_std" readonly> c 
            </div>
        </div>
        <div class="row__cell cell"><textarea class="textareacss read" readonly name="comments_plan_read[]" id="test" cols="30" rows="3">{{$arTask->comments_plan}}</textarea></div>
        <div class="row__cell cell">
            <a href="https://teamteam.bitrix24.ru/company/personal/user/210/tasks/task/view/{{$arTask->taskIdB24}}/" target="_blank"> Ссылка на задачу</a>
        </div>

    </div>
    @endforeach

    <input type="submit" value="сохранить">


</form>
<script>
url = "{{url('task/addPlan')}}";
initAutocomplete()

var datepicker = $('.datepicker-here').datepicker().data('datepicker');
if($('#DATE').attr('data-date') != ""){
    selectedDate = new Date($('#DATE').attr('data-date'))
}
else{
    selectedDate = new Date()

}
datepicker.selectDate(selectedDate)
// datepicker.update('minDate', new Date())
$('.datepicker-here').datepicker({
    onSelect: ()=>{
        changeField()
    }
})
function changeField(){
    debugger
    fakeUrl =  url + ("{{addB24Auth()}}") +"&DATE=" + $('#DATE').val() + '&USER_ID=' + $('#USER_ID').val() 
    fakeUrl = fakeUrl.split('&amp;');
    fakeUrl = fakeUrl.join('&');

    window.location.href =fakeUrl
}
</script>
@endsection