<div wire:ignore.self class="modal fade" id="modal-list" tabindex="-1" aria-labelledby="talentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"">
        <div class="modal-content" style="width: fit-content">
            <div class="modal-header">
                <h5 class="modal-title" id="talentModalLabel">Talents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal"></button>
            </div>
            <div class="modal-body">
                <livewire:talents/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal"
                        data-bs-dismiss="modal">Close
                </button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
