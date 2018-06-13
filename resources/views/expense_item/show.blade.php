@extends('layouts.app')

@section('content')
    {!! Form::open(['method'=>'delete', 'route'=>['expense.destroy', $expense_id]]) !!}
    <input type="image" src="images/close.png" alt="Manage Items" onclick="return confirm('Do you want to Cancel?')" data-toggle="tooltip" data-placement="right" title="Cancel"/>
    {!! Form::close() !!}
    <table class="table table-bordered table-responsive" style="margin-top: 10px;">
        <thead>
            <tr>
                <th>ID    
                </th>
                <th>expense_id</th>
                <th>description</th>
                <th>category</th>
                <th>amount</th>
                <th>GST</th>
                <th>PST</th>
                <th colspan="3"><form method="get" action="/expenseitem/create">
                    <input type="hidden" name = "expense_id" value='{{$expense_id
                    }}'><!-- Button trigger modal -->
                        <button type="button"  data-toggle="modal" data-target="#addItem" data-toggle="tooltip" data-placement="right" title="Add an Item"><img src="images/add.png"></button>
                    </form></th>
            </tr>
        </thead>
        <tbody>
        @foreach($expense_items as $expense_item)
            <tr>
                <td>{{ $expense_item->id }}</td>
                <td>{{ $expense_item->expense_id }}</td>
                <td>{{ $expense_item->description }}</td>
                <td>{{ $expense_item->category }}</td>
                <td>{{ "$" . $expense_item->amount }}</td>
                <td>{{ "$" . $expense_item->gst }}</td>
                <td>{{ "$" . $expense_item->pst }}</td>

                <td>
                    <a href="{{ route('expenseitem.edit', $expense_item->id) }}" class="btn btn-success">Edit</a></td>
                   <td> {!! Form::open(['method'=>'delete', 'route'=>['expenseitem.destroy', $expense_item->id]]) !!}
                    {!! Form::submit('Delete', ['class'=>'btn btn-danger', 'onclick'=>'return confirm("Do you want to delete this record?")']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
      @endforeach
        </tbody>
    </table>
<!-- Modal -->
<div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="right" title="Cancel"><img src="images/close.png"></button>
        
      </div>
      <div class="modal-body modal-xl">
        @include('expense_item.create')  
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->
@stop