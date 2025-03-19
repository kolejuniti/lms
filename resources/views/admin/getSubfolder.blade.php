<div id="material-directory" class="row g-4">
    <!-- Back Buttons Section -->
    @if (isset($prev0))
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="material-card back-folder hover-lift" onclick="tryerr0('{{ $prev0->CourseID }}','{{ $prev0->Addby }}','{{ $prev0->SessionID }}')">
            <div class="icon-container">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                </svg>
            </div>
            <div class="content-name back-label">
                <i class="ti ti-arrow-left me-2"></i> Back
            </div>
        </div>
    </div>
    @elseif (isset($prev))
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="material-card back-folder hover-lift" onclick="tryerr('{{ $prev }}')">
            <div class="icon-container">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                </svg>
            </div>
            <div class="content-name back-label">
                <i class="ti ti-arrow-left me-2"></i> Back
            </div>
        </div>
    </div>
    @elseif (isset($prev2))
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="material-card back-folder hover-lift" onclick="tryerr2('{{ $prev2 }}')">
            <div class="icon-container">
                <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                </svg>
            </div>
            <div class="content-name back-label">
                <i class="ti ti-arrow-left me-2"></i> Back
            </div>
        </div>
    </div>
    @endif

    <!-- Main Folders -->
    @if (isset($folder))
        @foreach ($folder as $fdr)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
            <div class="material-card folder-card hover-lift" onclick="tryerr('{{ $fdr->DrID }}')">
                <div class="icon-container">
                    <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                        <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                        <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                        <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                    </svg>
                </div>
                <div class="content-name" title="{{ $fdr->DrName }}">
                    {{ $fdr->DrName }}
                    @if($fdr->Password != null)
                    <span class="password-icon" title="Password Protected">
                        <i class="fa fa-lock"></i>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <!-- Chapter Folders -->
    @if (isset($subfolder))
        @foreach ($subfolder as $sfdr)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
            <div class="material-card folder-card hover-lift" onclick="tryerr2('{{ $sfdr->DrID }}')">
                <div class="chapter-badge">Chapter {{ $sfdr->ChapterNo }}</div>
                <div class="icon-container">
                    <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                        <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                        <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                        <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                    </svg>
                </div>
                <div class="content-name" title="{{ $sfdr->DrName }}">
                    {{ $sfdr->DrName }}
                    @if($sfdr->Password != null)
                    <span class="password-icon" title="Password Protected">
                        <i class="fa fa-lock"></i>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <!-- Sub-Chapter Folders -->
    @if (isset($subfolder2))
        @foreach ($subfolder2 as $sfdr)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
            <div class="material-card folder-card hover-lift" onclick="tryerr3('{{ $sfdr->DrID }}')">
                <div class="chapter-badge">Chapter {{ $sfdr->SubChapterNo }}</div>
                <div class="icon-container">
                    <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                        <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                        <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                        <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                    </svg>
                </div>
                <div class="content-name" title="{{ $sfdr->DrName }}">
                    {{ $sfdr->DrName }}
                    @if($sfdr->Password != null)
                    <span class="password-icon" title="Password Protected">
                        <i class="fa fa-lock"></i>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <!-- Files Section -->
    @if (isset($classmaterial))
        @foreach ($classmaterial as $mats)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
            <div class="material-card file-card hover-lift">
                <a href="{{ Storage::disk('linode')->url($mats) }}" target="_blank" class="file-link">
                    <div class="icon-container">
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
                    </div>
                    <div class="file-extension-badge">{{ pathinfo(storage_path($mats), PATHINFO_EXTENSION) }}</div>
                    <div class="content-name" title="{{ basename($mats) }}">
                        {{ basename($mats) }}
                    </div>
                </a>
                <div class="file-actions">
                    <a href="{{ Storage::disk('linode')->url($mats) }}" target="_blank" class="action-btn preview" title="Preview">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="{{ Storage::disk('linode')->url($mats) }}" download class="action-btn download" title="Download">
                        <i class="fa fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <!-- Attendance Tool Card -->
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fade-in" style="animation-delay: 0.3s">
        <div class="material-card tool-card attendance-card hover-lift">
            <a href="/admin/attendance/report" class="tool-link">
                <div class="icon-container">
                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 233.000000 217.000000" preserveAspectRatio="xMidYMid meet">
                        <g transform="translate(0.000000,217.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                        <path d="M1003 2040 c-48 -19 -60 -99 -18 -126 l24 -16 -19 -39 -20 -39 -65 0
                        -65 0 0 -80 0 -80 255 0 255 0 0 80 0 80 -53 0 c-51 0 -54 2 -74 37 l-21 36
                        29 24 c16 14 29 34 29 44 0 28 -26 75 -45 83 -25 9 -187 6 -212 -4z m192 -70
                        c0 -8 -10 -16 -22 -18 -39 -6 -46 -26 -24 -71 12 -22 21 -45 21 -51 0 -6 -27
                        -10 -65 -10 -36 0 -65 3 -65 8 0 4 9 22 21 41 28 46 22 74 -16 81 -16 3 -29 9
                        -27 13 1 4 2 12 2 17 0 7 32 10 88 8 69 -2 87 -6 87 -18z"/>
                        <path d="M549 1785 c-62 -34 -59 5 -59 -776 l0 -711 34 -34 34 -34 296 0 296
                        0 -17 35 -17 35 -261 0 c-241 0 -263 1 -278 18 -16 17 -17 80 -17 700 0 788
                        -15 702 124 702 l86 0 0 40 0 40 -97 0 c-65 -1 -107 -6 -124 -15z"/>
                        <path d="M1420 1760 l0 -40 88 0 c55 0 92 -4 100 -12 9 -9 12 -118 12 -429 l0
                        -417 24 -6 c13 -3 31 -9 40 -12 14 -6 16 35 16 430 0 479 0 480 -60 511 -20
                        10 -59 15 -125 15 l-95 0 0 -40z"/>
                        <path d="M708 1504 c-35 -19 -48 -43 -48 -92 0 -70 70 -115 135 -88 41 17 56
                        38 62 85 5 36 2 44 -30 76 -39 39 -73 44 -119 19z m75 -25 c65 -30 46 -129
                        -25 -129 -78 0 -93 106 -18 133 8 3 16 6 17 6 1 1 13 -4 26 -10z"/>
                        <path d="M953 1434 c-3 -9 -2 -24 4 -33 9 -14 43 -16 284 -16 l274 0 0 30 0
                        30 -278 3 c-250 2 -278 1 -284 -14z"/>
                        <path d="M714 1220 c-58 -23 -74 -117 -28 -164 20 -19 37 -26 66 -26 50 0 74
                        13 93 50 24 46 19 78 -19 116 -35 35 -68 42 -112 24z m69 -31 c65 -30 46 -129
                        -25 -129 -78 0 -93 106 -18 133 8 3 16 6 17 6 1 1 13 -4 26 -10z"/>
                        <path d="M954 1146 c-3 -8 -4 -23 -2 -33 3 -17 21 -18 283 -18 l280 0 0 30 0
                        30 -278 3 c-236 2 -278 0 -283 -12z"/>
                        <path d="M714 930 c-58 -23 -73 -114 -27 -162 20 -21 36 -28 66 -28 49 0 73
                        13 92 50 24 46 19 78 -19 116 -35 35 -68 42 -112 24z m69 -31 c50 -23 55 -93
                        8 -118 -74 -40 -140 56 -75 108 31 24 35 25 67 10z"/>
                        <path d="M968 869 c-10 -5 -18 -18 -18 -27 0 -37 20 -42 166 -42 l143 0 73 40
                        73 40 -210 0 c-124 -1 -217 -5 -227 -11z"/>
                        <path d="M1420 781 c-95 -29 -184 -113 -220 -207 -44 -119 -13 -261 79 -353
                        71 -71 184 -108 284 -91 274 44 378 382 176 572 -65 60 -137 88 -227 87 -37 0
                        -78 -4 -92 -8z m311 -157 c11 -13 -10 -38 -157 -185 l-169 -169 -78 78 -78 78
                        20 25 20 24 58 -57 58 -58 140 140 c77 77 147 140 156 140 9 0 22 -7 30 -16z"/>
                        <path d="M689 621 c-63 -63 -23 -171 64 -171 21 0 47 5 58 10 22 12 49 61 49
                        89 0 88 -109 134 -171 72z m94 -12 c50 -23 55 -93 8 -118 -74 -40 -140 56 -75
                        108 31 24 35 25 67 10z"/>
                        <path d="M953 564 c-13 -34 11 -49 79 -52 l66 -3 4 36 4 35 -73 0 c-59 0 -75
                        -3 -80 -16z"/>
                        </g>
                    </svg>
                </div>
                <div class="content-name">
                    ATTENDANCE
                </div>
            </a>
        </div>
    </div>

    <!-- Student Report Tool Card -->
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fade-in" style="animation-delay: 0.35s">
        <div class="material-card tool-card report-card hover-lift">
            <a href="/admin/report/student" class="tool-link">
                <div class="icon-container">
                    <svg width="4em" height="4em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7C3 6.46957 3.21071 5.96086 3.58579 5.58579C3.96086 5.21071 4.46957 5 5 5H19C19.5304 5 20.0391 5.21071 20.4142 5.58579C20.7893 5.96086 21 6.46957 21 7V17C21 17.5304 20.7893 18.0391 20.4142 18.4142C20.0391 18.7893 19.5304 19 19 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V7Z" stroke="#2C3E50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 5V4C7 3.46957 7.21071 2.96086 7.58579 2.58579C7.96086 2.21071 8.46957 2 9 2H15C15.5304 2 16.0391 2.21071 16.4142 2.58579C16.7893 2.96086 17 3.46957 17 4V5" stroke="#2C3E50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 9.25H11.5" stroke="#2C3E50" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 12.25H14.5" stroke="#2C3E50" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 15.25H10" stroke="#2C3E50" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12.5 15.25C12.5 15.25 14 15.25 15.5 15.25C17 15.25 17 13.75 15.5 13.75C14 13.75 14 12.25 15.5 12.25C17 12.25 17 10.75 15.5 10.75H14" stroke="#ec4899" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="content-name">
                    STUDENT REPORT
                </div>
            </a>
        </div>
    </div>

    <!-- Empty State (if no content) -->
    @if (!isset($folder) && !isset($subfolder) && !isset($subfolder2) && !isset($classmaterial) && !isset($prev) && !isset($prev0) && !isset($prev2))
    <div class="col-12">
        <div class="empty-state">
            <i class="ti-folder-open"></i>
            <h5>No Content Available</h5>
            <p class="text-muted">This folder is currently empty.</p>
        </div>
    </div>
    @endif
</div>

<style>
/* Material Cards Styling */
.material-card {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    background-color: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    height: 100%;
    text-align: center;
    padding: 20px 15px;
    cursor: pointer;
}

.material-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.material-card a {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

.icon-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 10px;
    transition: all 0.3s ease;
}

.material-card:hover .icon-container {
    transform: scale(1.05);
}

.content-name {
    font-weight: 600;
    color: #334155;
    font-size: 0.95rem;
    margin-top: 15px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding: 0 15px;
    transition: all 0.3s ease;
}

.material-card:hover .content-name {
    color: #6366f1;
}

.back-label {
    color: #06b6d4;
}

/* Chapter Badge */
.chapter-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    z-index: 2;
    opacity: 0.9;
    box-shadow: 0 2px 5px rgba(99, 102, 241, 0.3);
}

.folder-card:hover .chapter-badge {
    opacity: 1;
}

/* Password Icon */
.password-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-left: 5px;
    color: #f43f5e;
    font-size: 14px;
}

/* File Extension Badge */
.file-extension-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #10b981;
    color: white;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    z-index: 2;
    box-shadow: 0 2px 5px rgba(16, 185, 129, 0.3);
}

/* File Action Buttons */
.file-actions {
    position: absolute;
    bottom: 10px;
    right: 10px;
    display: flex;
    gap: 5px;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

.file-card:hover .file-actions {
    opacity: 1;
    transform: translateY(0);
}

.action-btn {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    color: #334155;
    transition: all 0.3s ease;
}

.action-btn:hover {
    transform: translateY(-3px);
}

.action-btn.preview:hover {
    background: #6366f1;
    color: white;
}

.action-btn.download:hover {
    background: #10b981;
    color: white;
}

/* Card Types */
.folder-card {
    border-bottom: 3px solid #f4b459;
}

.back-folder {
    border-bottom: 3px solid #06b6d4;
}

.file-card {
    border-bottom: 3px solid #f15642;
}

.tool-card.attendance-card {
    border-bottom: 3px solid #06b6d4;
}

.tool-card.report-card {
    border-bottom: 3px solid #ec4899;
}

/* Animation Effects */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease forwards;
    opacity: 0;
}

/* Empty State */
.empty-state {
    padding: 60px;
    text-align: center;
    background: white;
    border-radius: 20px;
    margin: 20px 0;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.empty-state i {
    font-size: 48px;
    color: #6366f1;
    margin-bottom: 20px;
    opacity: 0.4;
}

/* Hover Effects */
.hover-lift:hover {
    transform: translateY(-5px);
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .material-card {
        margin-bottom: 15px;
    }
}

@media (max-width: 768px) {
    .content-name {
        font-size: 0.85rem;
    }
}

@media (max-width: 576px) {
    .material-card {
        padding: 15px 10px;
    }
}
</style>

<script>
// Apply animations to items
document.addEventListener('DOMContentLoaded', function() {
    // Apply fade-in animation to items
    const items = document.querySelectorAll('.material-card');
    items.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('fade-in');
        }, index * 50);
    });
    
    // Add tooltip functionality
    document.querySelectorAll('[title]').forEach(el => {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = el.getAttribute('title');
        tooltip.style.display = 'none';
        document.body.appendChild(tooltip);
        
        el.addEventListener('mouseenter', function(e) {
            const rect = el.getBoundingClientRect();
            tooltip.style.display = 'block';
            tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
            tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)}px`;
        });
        
        el.addEventListener('mouseleave', function() {
            tooltip.style.display = 'none';
        });
    });
});
</script>