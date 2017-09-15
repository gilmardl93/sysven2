@extends('layouts.admin')

@section('css-style')
{!! Html::style('assets/global/plugins/select2/css/select2.min.css') !!}
{!! Html::style('assets/global/plugins/select2/css/select2-bootstrap.min.css') !!}
@stop

@section('title') USUARIOS
@stop

@section('titulo') USUARIOS
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="portlet light bordered">
                <div class="portlet-title">ACTUALIZAR USUARIO</div>
                <div class="portlet-body">
                {!! Form::open(['method' => 'POST', 'route' => 'usuario.actualizar']) !!}
                @foreach($usuario as $row)
                <input type="hidden" name="id" value="{!! $row->id !!}">
                {!! Field::text('username',$row->username) !!}
                {!! Field::password('password') !!}
                {!! Field::text('nombres',$row->nombres) !!}
                {!! Field::text('paterno',$row->paterno) !!}
                {!! Field::text('materno',$row->materno) !!}
                <label>ROL</label>
                {!! Form::select('rol',rol($row->idrol), null, ['id' => 'Rol','class' => 'form-control']) !!}
                <br>
                <label>TIENDA</label>
                {!! Form::select('tienda',tienda($row->idtienda) , null, ['id' => 'Tienda','class' => 'form-control']) !!}
                <br>
                {!! Form::submit('ACTUALIZAR', ['class' => 'btn default green-stripe']) !!}
                <a href="{!! url('usuario') !!}" class="btn default red-stripe">ATRAS</a>
                @endforeach
                {!! Form::close() !!}
                </div> 
            </div>
        </div>
    </div>
@endsection

@section('js-script')
{!! Html::script('assets/global/plugins/select2/js/select2.full.min.js') !!}
<script>


    $.fn.select2.defaults.set("theme", "bootstrap");
    
    $("#Tienda").select2({
        width:'auto',
        ajax: {
            url: '{{ url("listado-tiendas-json") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    varsearch: params.term 
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        placeholder : 'Buscar Producto: ejemplo TIENDA 01',
        minimumInputLength: 1,
        templateResult: tienda,
        templateSelection: tienda,
        escapeMarkup: function(markup) {
            return markup;
        } 
    });
    function tienda(pro){
        var markup = pro.text;
        return markup;
    }
    
    $("#Rol").select2({
        width:'auto',
        ajax: {
            url: '{{ url("listado-roles-json") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    varsearch: params.term 
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        placeholder : 'Buscar Producto: ejemplo ADMINISTRADOR',
        minimumInputLength: 1,
        templateResult: rol,
        templateSelection: rol,
        escapeMarkup: function(markup) {
            return markup;
        } 
    });
    function rol(pro){
        var markup = pro.text;
        return markup;
    }

</script>
@stop