
{!! Form::open(['url'=>'expense']) !!}
<table>
    <tr>
        <th>{!! Form::label('supplier', 'Supplier') !!}</th>
        <th>{!! Form::label('invoice', 'Invoice') !!}</th>
        <th>{!! Form::label('date', 'Date') !!}</th>
    </tr>
    <!--//This should be in a loop that automatically fill our certain data-->
    <tr>
        <td>{!! Form::text('supplier')!!}
            {!! $errors->has('supplier')?$errors->first('supplier'):'' !!}</td>
        <td>{!! Form::text('invoice', null)!!}
            {!! $errors->has('invoice')?$errors->first('invoice'):'' !!}</td>
        <td>{!! Form::date('date', now()) !!} </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>

{!! Form::close() !!}
