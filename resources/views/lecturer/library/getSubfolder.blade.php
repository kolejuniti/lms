<div id="material-directory" class='row'>

    @if (isset($folder))
        @foreach ($folder as $fdr)
        <div class="col-md-3 text-center">
            <a href="#" onclick="tryerr('{{ $fdr->DrID }}')">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                </svg>
                <div class="p-3">
                    {{ $fdr->DrName }}ss  &nbsp <i class="{{ ($fdr->Password != null) ? 'fa fa-lock' : '' }}"></i>
                </div>
            </a>  
        </div>
        @endforeach
    @else
        @if (isset($prev0))
        <div class="col-md-3 text-center">
            <a href="#" onclick="getContent('{{ $prev0->Addby }}')">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/></svg>
                <div class="p-3">
                    <i class="ti ti-more-alt"></i> 
                </div>
            </a>
        </div>
        @elseif (isset($prev))
        <div class="col-md-3 text-center">
            <a href="#" onclick="tryerr('{{ $prev }}')">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/></svg>
                <div class="p-3">
                    <i class="ti ti-more-alt"></i> 
                </div>
            </a>
        </div>
        @elseif (isset($prev2))
        <div class="col-md-3 text-center">
            <a href="#" onclick="tryerr2('{{ $prev2 }}')">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/></svg>
                <div class="p-3">
                    <i class="ti ti-more-alt"></i> 
                </div>
            </a>
        </div>
        @endif
    @endif

    @if (isset($subfolder))
        @foreach ($subfolder as $sfdr)
        <div class="col-md-3 text-center">
            <a href="#" onclick="tryerr2('{{ $sfdr->DrID }}')">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                </svg>
                <div class="p-3">
                    Chapter {{ $sfdr->ChapterNo }} : {{ $sfdr->DrName }}  &nbsp <i class="{{ ($sfdr->Password != null) ? 'fa fa-lock' : '' }}"></i>
                </div>
            </a>  
        </div>
        @endforeach
    @endif

    @if (isset($subfolder2))
        @foreach ($subfolder2 as $sfdr)
        <div class="col-md-3 text-center">
            <a href="#" onclick="tryerr3('{{ $sfdr->DrID }}')">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                </svg>
                <div class="p-3">
                    Chapter {{ $sfdr->SubChapterNo }} : {{ $sfdr->DrName }}  &nbsp <i class="{{ ($sfdr->Password != null) ? 'fa fa-lock' : '' }}"></i>
                </div>
            </a>  
        </div>
        @endforeach
    @endif

    @if (isset($classmaterial))
        @foreach ($classmaterial as $mats)
        <div class="col-md-3 text-center">
            <a href="{{ Storage::disk('linode')->url($mats) }}" target="_blank">
                <svg width="4em" height="4em" enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" >
                    <path d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z" fill="#E2E5E7"/>
                    <path d="m384 128h96l-128-128v96c0 17.6 14.4 32 32 32z" fill="#B0B7BD"/>
                    <polygon points="480 224 384 128 480 128" fill="#CAD1D8"/>
                    <path d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16  V416z" fill="#F15642"/>
                    <g fill="#fff">
                        <path d="m101.74 303.15c0-4.224 3.328-8.832 8.688-8.832h29.552c16.64 0 31.616 11.136 31.616 32.48 0 20.224-14.976 31.488-31.616 31.488h-21.36v16.896c0 5.632-3.584 8.816-8.192 8.816-4.224 0-8.688-3.184-8.688-8.816v-72.032zm16.88 7.28v31.872h21.36c8.576 0 15.36-7.568 15.36-15.504 0-8.944-6.784-16.368-15.36-16.368h-21.36z"/>
                        <path d="m196.66 384c-4.224 0-8.832-2.304-8.832-7.92v-72.672c0-4.592 4.608-7.936 8.832-7.936h29.296c58.464 0 57.184 88.528 1.152 88.528h-30.448zm8.064-72.912v57.312h21.232c34.544 0 36.08-57.312 0-57.312h-21.232z"/>
                        <path d="m303.87 312.11v20.336h32.624c4.608 0 9.216 4.608 9.216 9.072 0 4.224-4.608 7.68-9.216 7.68h-32.624v26.864c0 4.48-3.184 7.92-7.664 7.92-5.632 0-9.072-3.44-9.072-7.92v-72.672c0-4.592 3.456-7.936 9.072-7.936h44.912c5.632 0 8.96 3.344 8.96 7.936 0 4.096-3.328 8.704-8.96 8.704h-37.248v0.016z"/>
                    </g>
                    <path d="m400 432h-304v16h304c8.8 0 16-7.2 16-16v-16c0 8.8-7.2 16-16 16z" fill="#CAD1D8"/>
                </svg>
                <div class="p-3">
                    {{ basename($mats )}} / {{ $extension = pathinfo(storage_path($mats), PATHINFO_EXTENSION); }}
                </div>
            </a>
        </div>
        @endforeach
    @endif

</div>