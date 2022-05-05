<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage your Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w3-container">
            <a href="{{ route('sheets.index') }}" class="w3-block w3-left w3-margin-bottom"><button class="w3-button w3-grey w3-margin-top" id="submit_button" name="submit_button" type="submit">
                BACK TO FILES
            </button></a>
            
            <?php
            echo '<div class="w3-xlarge w3-panel">Your Document: '.$file[0]['name'].'</div>';
            if(empty($rows) || is_null($rows)) {
                echo '<div class="w3-medium w3-panel w3-center">No Rows Added yet</div>';
            }
            ?>

            <table class="w3-table w3-border">
                <?php
                    $columns = count($header);
                    echo '<tr>';
                        for ($i=0; $i < $columns ; $i++) { 
                    ?>
                            
                            <th class="w3-grey">
                                <?php echo $header[$i]['content']; ?>
                            </th>

                    <?php
                        }
                    echo '<tr>';
                    $counter = 0;
                    foreach ($rows as $row) {
                        ++$counter;
                        echo $counter == 1 ? '<tr>' : '';
                        ?>
                            <td class="w3-border">
                                <?php echo $row['content']; ?>
                            </td>

                        <?php
                        echo $counter == $columns ? '<tr>' : '';
                        $counter == $columns ? $counter = 0 : $counter = $counter;
                    }
                    
                    ?>
            </table> 
            <br><br>
            <div class="w3-medium wr-grey">
                Add New Row
            </div>
            <div  action="">
                <div class="">
                    <?php
                for ($i=0; $i < $columns ; $i++) { 
                    ?>
                        <div class="w3-col s6 m4 l3 w3-padding">
                            <label><?php echo $header[$i]['content']; ?></label>

                            <input class="w3-input new_cells">
                            <input hidden class="column_no" value="<?php echo $i+1 ?>">
                        </div>
                    <?php
                        }
                    ?>
                </div>
                <input id="file_id" name="file_id" hidden value="<?php echo $file[0]['id'] ?>"/>
                <button class="w3-button w3-black w3-margin-top" onclick="submitter(this)" id="submit_button" name="submit_button" type="submit">ADD RECORD</button>
                    </div>
            
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>  
        function submitter(e) {
            var inputs = document.getElementsByClassName('new_cells');
            var file_id = document.getElementById('file_id');
            console.log(file_id);
            var columns = document.getElementsByClassName('column_no');
            var records = [];
            var cols = [];
            var ifEmpty = "";

            for (let index = 0; index < inputs.length; index++) {
                const  value = inputs[index].value; 
                const  column = columns[index].value; 
                const record = {
                    content: value,
                    column: column,
                };
                records.push(record);  
                ifEmpty += value;           
            }

            ifEmpty == '' ?  alert('please fill in at least a field') : '';

            if(ifEmpty != '') {
                var file_id = file_id.value;
                console.log(data);
                var data = {
                    file_id: file_id,
                    records: records,
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "PUT",
                    url: `/sheets/${file_id}`,
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        console.log(file_id);
                        messenger(response, file_id);
                    }
                });
                function messenger(data, file_id) {             
                    alert(data.message);
                    document.location.reload(true);
                    console.log(file_id);
                }
            } 
            
        } 
    </script>
</x-app-layout>