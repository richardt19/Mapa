@extends('layout')
@section('content')
    <div class="page-header">
        <h2>Bienvenido</h2>
    </div>
    <h3>
        <span class="label label-primary">Elige el numero de conductores y clientes</span>
    </h3>
    <form name="form1" method="POST">

    {!! csrf_field() !!}
    <div class="row">
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>Número de conductores:</th>
                        <th>Número de clientes:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="number" name="N_Conductores" min="1" max="10" required></td>
                        <td><input type="number" name="N_Clientes" min="1" max="10" required></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Aceptar</button>
        </div>
    </div>
    </form>
@endsection