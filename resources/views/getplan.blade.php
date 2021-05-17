
@extends('layouts.template')
@section('main')
@csrf

<form action="" method="post">
    <input type="text" name="date" class="datepicker-here input_std" data-auto-close="true" data-date="{{isset($_REQUEST['date'])?$_REQUEST['date']:""}}">
    @if ($isAdmin)
        <select class="input_std" name="user_id" id="">
            @foreach ($arUsers as $arUser)
            @if ($arUser->ID == $selectedUser)
            <option  selected value="{{$arUser->ID}}">{{$arUser->LAST_NAME.' ' . $arUser->NAME}}</option>
            
        @else
            <option   value="{{$arUser->ID}}">{{$arUser->LAST_NAME.' ' . $arUser->NAME}}</option>
            
        @endif
            @endforeach
        </select>
    @endif
    <input class="input_std input_std_sumbit"type="submit" value="Применить">
        <style>
            td{
                border:1px solid black;
            }
            th{
                border:1px solid black;
            }
            .red td{
                background: rgb(255, 199, 199);
    
            }
            .green td{
                background: rgb(181, 255, 181);

            }
        </style>
    <table style="k">
        <thead>
            <tr>
                <th rowspan="2" width="40%">Задача</th>
                <th colspan="2" width="20%">План</th>
                <th colspan="2" width="20%">Факт</th>
                <th rowspan="2" width="10%">Выполнен</th>
                @if ($isAdmin)
                <th rowspan="2" width="10%">Действия</th>       
                @endif               
            </tr>
            <tr>
                <th width="10%">План коммент</th>
                <th width="10%">План время</th>
                <th width="10%">Факт коммент</th>
                <th width="10%">Факт время</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($arTasks as $item)
            <tr class="{{$item->completed == 0?"red":"green"}}">
                <td>
                    <a href="https://teamteam.bitrix24.ru/company/personal/user/{{$selectedUser}}/tasks/task/view/{{$item->taskIdB24}}/" target="_blank"> {{$item->titleTask}}</a>
                </td>
                <td>
                    {{$item->comments_plan}}
                </td>
                <td>
                    {{$arResult[$item->id]['plan']['hours']}} ч   {{$arResult[$item->id]['plan']['minute']}} м   {{$arResult[$item->id]['plan']['time']}} с
                </td>
                <td>
                    {{$item->comments}}
                </td>
                <td>
                    {{$arResult[$item->id]['fact']['hours']}} ч   {{$arResult[$item->id]['fact']['minute']}} м   {{$arResult[$item->id]['fact']['time']}} с
                </td>
                <td>
                    {{$item->completed == 0?"Нет":"Да"}}
                </td>
                @if ($isAdmin)
                {{addB24Auth(true)}}
                <td>
                    <button class="popup-open">Удалить</button>
                    <div class="popup-fade">
                        <div class="popup">
                            <form action="{{ url('task/'.$item->id.'/deleteTask') }}" method="POST">
                            {{addB24Auth(true)}}
                                <p>Вы уверены?</p>
                                <button type="submit">Да</button>
                                <button type="button" class="popup-close">Не уверен</button>    
                            </form>
                        </div>		
                    </div>  
                </td>       
                @endif
                
            </tr>

         @endforeach

        </tbody>
        <tr>
            <td>Итого:</td>
            <td colspan="2">
                {{$arResult['ALL_TIME']['PLAN']['hours']}} ч   {{$arResult['ALL_TIME']['PLAN']['minute']}} м   {{$arResult['ALL_TIME']['PLAN']['time']}} с
            
            </td>
            <td colspan="2"> {{$arResult['ALL_TIME']['FACT']['hours']}} ч   {{$arResult['ALL_TIME']['FACT']['minute']}} м   {{$arResult['ALL_TIME']['FACT']['time']}} с</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">
                <p>{{$arResult['message']}}</p>
            </td>
            <td colspan="4">    
            {{$arResult['total_time_left']['hours']}} ч   {{$arResult['total_time_left']['minute']}} м   {{$arResult['total_time_left']['time']}} с
            </td>
        </tr>
    </table>
    

</form>
<script>

var datepicker = $('.datepicker-here').datepicker().data('datepicker');
if($('.datepicker-here').attr("data-date") != ""){
    selectedDate = new Date($('.datepicker-here').attr("data-date"))
}
else{
    selectedDate = new Date()

}
datepicker.selectDate(selectedDate)


$(document).ready(function($) {
	$('.popup-open').click(function() {
		$(this).next().fadeIn();
		return false;
	});	
	
	$('.popup-close').click(function() {
		$(this).parents('.popup-fade').fadeOut();
		return false;
	});		
 
	$(document).keydown(function(e) {
		if (e.keyCode === 27) {
			e.stopPropagation();
			$('.popup-fade').fadeOut();
		}
	});
	
	$('.popup-fade').click(function(e) {
		if ($(e.target).closest('.popup').length == 0) {
			$(this).fadeOut();					
		}
	});
});
</script>
@endsection