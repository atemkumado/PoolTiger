<div wire:ignore.self class="modal fade" id="modal-list" tabindex="-1" aria-labelledby="talentModalLabel"
     aria-hidden="true" id="modalblue">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="width: max-content; height: 80%">
            <div class="modal-header" style="background-color: rgba(255,178,126,0.47)">
                <h6 class="modal-title" style="padding-left: 10px"><strong>Talents</strong></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                        style="color: black"></span>X
                </button>
            </div>
            <div class="modal-body">
                <livewire:talents :data="$talents"/>
            </div>
            <div class="modal-footer" style="min-height: 40px">
                {{--                <button type="button" class="btn btn-secondary" wire:click="closeModal"--}}
                {{--                        data-bs-dismiss="modal">Close--}}
                {{--                </button>--}}
                {{--                <button type="submit" class="btn btn-primary">Save</button>--}}
            </div>
        </div>
    </div>
</div>
