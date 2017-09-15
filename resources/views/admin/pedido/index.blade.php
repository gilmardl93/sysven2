@extends('layouts.admin')

@section('css-style')
{!! Html::style('assets/global/plugins/select2/css/select2.min.css') !!}
{!! Html::style('assets/global/plugins/select2/css/select2-bootstrap.min.css') !!}
{!! Html::style('assets/global/plugins/datatables/datatables.min.css') !!}
{!! Html::style('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') !!}
@stop


@section('title') PEDIDOS
@stop

@section('titulo') PEDIDOS
@stop

@section('content')

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session('eliminar'))
        <div class="alert alert-danger">
            {{ session('eliminar') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">REGISTRAR PEDIDO</div>
                <div class="portlet-body">
                {!! Form::open(['method' => 'POST', 'route' => 'pedido.registrar']) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::select('producto',[], null, ['id' => 'Producto', 'class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::text('cantidad',1, ['placeholder' => 'CANTIDAD']) !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::submit('REGISTRAR', ['class' => 'btn default green-stripe']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">LISTA DE PEDIDOS</div>
                <div class="portlet-body">
                <table class="table table-bordered table-hover Marcas" >
                    <thead>
                        <tr>
                            <th> Producto </th>
                            <th> Categoria </th>
                            <th> Marca </th>
                            <th> Presentacion </th>
                            <th> Fecha - Hora </th>
                            <th> Accion </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-script')

{!! Html::script('assets/global/plugins/select2/js/select2.full.min.js') !!}
{!! Html::script('assets/global/scripts/datatable.js') !!}
{!! Html::script('assets/global/plugins/datatables/datatables.min.js') !!}
{!! Html::script('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') !!}

<script>
    $('.Marcas').dataTable({
        "language": {
            "emptyTable": "No hay datos disponibles",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ filas",
            "search": "Buscar :",
            "lengthMenu": "_MENU_ registros"
        },
        "bProcessing": true,
        "sAjaxSource": '{{ url('listado-pedidos') }}',
        "pagingType": "bootstrap_full_number",
        "columnDefs": [
                    { 
                        'orderable': false,
                        'targets': '_all'
                    },
                    {
                        'targets':5,
                        'render': function ( data, type, row ) {
                        return ' \
                        <a href="eliminar-pedido/'+row.id+' " title="Eliminar" class="btn btn-icon-only red" ><i class="fa fa-trash"></i></a> \
                        ';
                        }
                    }
                ],
        "columns": [
                { "data": "producto","defaultContent": "" },
                { "data": "categoria","defaultContent": "" },
                { "data": "marca","defaultContent": "" },
                { "data": "presentacion","defaultContent": "" },
                { "data": "fecha_hora","defaultContent": "" },

            ]


    });

    $("#Producto").select2({
        width:'auto',
        ajax: {
            url: '{{ url("listado-productos-json") }}',
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
        placeholder : 'Buscar Producto: ejemplo LIMA',
        minimumInputLength: 1,
        templateResult: producto,
        templateSelection: producto,
        escapeMarkup: function(markup) {
            return markup;
        } 
    });
    function producto(pro){
        var markup = pro.text + " - " + pro.pre ;
        return markup;
    }
</script>

@stop