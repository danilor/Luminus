<table class="table table-striped">
    <thead>
        <tr>
            <th><center>{{ "SELECCIONAR"  }}</center></th>
            <th><center>{{ "NOMBRE" }}</center></th>
        </tr>
    </thead>
    <tbody>
        @foreach( \Modules\FaqsDocument::all() AS $document )
        <tr>
            <td>
                <center>
                    <input type="checkbox" value="[doc={{ $document->key  }}||{{$document->title}}]" id="" class="document_check_modal" />
                </center>
            </td>
            <td><center>{{ $document->title  }}</center></td>
        </tr>
        @endforeach
    </tbody>
</table>