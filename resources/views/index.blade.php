
@extends('layouts.template')
@section('main')
@csrf
@if ($isAdmin)
<a href="{{url("/task/addPlan").addB24Auth()}}" id="addPlan">Добавить план</a>
    
@endif


<script>

</script>
@endsection