<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage your Files') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w3-container">
            <a href="{{ route('dashboard') }}" class="w3-block w3-left w3-margin-bottom"><button class="w3-button w3-grey w3-margin-top" id="submit_button" name="submit_button" type="submit">
                DASHBOARD
            </button></a>
            
            <?php
            $files = $files;
            echo '<div class="w3-xlarge w3-panel">Your files</div>';
            if(empty($files) || is_null($files)) {
                echo '<div class="w3-medium w3-panel w3-center">No File Added yet</div>';
            }
            foreach ($files as $file) {
                ?>
                <div class=" w3-padding w3-row-padding w3-white w3-margin-bottom w3-leftbar w3-border-gray">
                    <div class="w3-col s12 m7 w3-white w3-row">
                        <a href="{{ route('sheets.show', ['sheet' => $file['id']]) }}" method="GET" class="w3-col s12"/>
                            <button " class="w3-button w3-col s12 w3-light-grey w3-left-align">
                                <div><h3><?php echo $file['name']; ?></h3></div>
                                <div class="w3-medium"><?php echo $file['description']; ?></h5></div>
                                <div class="w3-small">Created <?php echo $file['created_at']; ?></h5></div>
                                
                                
                            </button>
                            
                        </a>
                    </div>
                </div>
                
                <hr>

                <?php
            }
            ?>
            
                
            </div>
            <br/>

        </div>
    </div>
</x-app-layout>