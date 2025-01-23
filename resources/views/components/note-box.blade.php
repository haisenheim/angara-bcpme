<div class="notation cursor-pointer">
    <div
        class="d-flex flex-column align-items-center justify-content-center angara-card-border infos-right"
    >
        <a class="text-black">
            <div class="note d-flex justify-content-between align-items-center gap-4">
                <div class="moyenne">{{ $moyenne }}</div>
                <div class="indicator d-flex flex-colum gap-1">
                    @if ($up)
                        <span class="up"></span>
                    @endif
                    @if ($down)
                        <span class="down"></span>
                    @endif
                </div>
            </div>

            <div class="txt-moyenne truncate" *ngIf="label">{{$label }}</div>
        </a>
    </div>
</div>