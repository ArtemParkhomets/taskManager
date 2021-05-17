
@extends('layouts.template')
@section('main')
@csrf
{{-- @dump($arResult) --}}
<form action="" method="post">
    {{addB24Auth(true)}}
    <table>
        <thead>
            <th>Задача</th>
            <th>Выполнен</th>
            <th>Комментарий</th>
            <th>Время(план)</th>
            <th>Время(факт)</th>
        </thead>
        @foreach ($arTasks as $arTask)
        <tr>
            <td> <label for="id_{{$arTask->id}}"> {{$arTask->titleTask}} </label></td>
            <td><input type="checkbox"  class="input_std" name="completed[{{$arTask->id}}]" id="id_{{$arTask->id}}" @if($arTask->completed) checked @endif></td>
            <td><input type="text"  class="input_std" name="comments[{{$arTask->id}}]" id="" value="{{$arTask->comments}}"></td>
            <td>{{ $arTask->timestamp_plan['hours'] }} ч {{ $arTask->timestamp_plan['minute'] }} м {{ $arTask->timestamp_plan['time'] }} с</td>
            <td>{{ $arTask->timestamp_fact['hours'] }} ч {{ $arTask->timestamp_fact['minute'] }} м {{ $arTask->timestamp_fact['time'] }} с</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $arResult['TIME']['PLAN']['hours'] }} ч {{ $arResult['TIME']['PLAN']['minute'] }} м {{  $arResult['TIME']['PLAN']['time'] }} с</td>
            <td>{{ $arResult['TIME']['FACT']['hours'] }} ч {{ $arResult['TIME']['FACT']['minute'] }} м {{ $arResult['TIME']['FACT']['time'] }} с</td>
        </tr>
    </table> 
    <input type="submit" class="input_std_sumbit input_std" value="Сохранить">
</form>

@endsection