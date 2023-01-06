@extends('layouts.front.hawdaj_master')
@section('content')
    <section class="filter-grid-section filter-grid-section--height">
        <div class="container">
            <h2 class="page-title mb-4">{{ $swalef->title ?? '' }}</h2>
            <div class="section-boxes container">
                <div class="inner-page">

                    <div class="card-img">
                        <img src="{{ asset($swalef->image ?? 'front_assets/imgs/our-services.jpg') }}">
                        {{-- <div class="date-type">
                            <div class="arte-type d-flex justify-content-center">
                                <span class="rate-project">
                                    <em class="rating-">
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        (5)
                                    </em>
                                </span>
                            </div>
                        </div> --}}
                    </div>
                    <div style="width:30px;"></div>
                    <div class="section-details">
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="date-type"> <span class=" mx-1"><strong>التاريخ</strong> <em>
                                            {{ $swalef->created_at ? date('d-m-Y', strtotime($swalef->created_at)) : '' }}</em></span>
                                </div>
                                <div class="date-type"> <span class="views mr-3"> <strong>الحالة</strong>
                                        <em>{{ $swalef->active ? 'منشور' : 'غير منشور' }}</em></span></div>


                            </div>
                        </div>


                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">

                            <div class="d-flex align-items-center gap">
                                <span> الوصف</span>
                            </div>

                            <p class="mb-0 mt-3"> {{ $swalef->description ?? '' }}</p>

                        </div>

                        <!-- description -->
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <h3 class="place-details__sub-title">المحتوي</h3>
                            @if ($swalef->type == 'file')
                                <div class="mb-3 download_link_area_in_course_content">
                                    @php
                                        $ext = pathinfo(asset($swalef->content), PATHINFO_EXTENSION);
                                    @endphp
                                    @if (in_array($ext, ['pdf', 'application/pdf']))
                                        <iframe height="700px" width="100%"
                                            src="{{ asset('storage/' . $swalef->content) }}#toolbar=0"
                                            class="mb-3"></iframe>
                                    @elseif (in_array($ext, ['png', 'jpg', 'jpeg']))
                                        <img
                                            src="{{ asset('storage/' . $swalef->content ?? 'front_assets/imgs/our-services.jpg') }}">
                                    @else
                                        <div
                                            class="d-flex justify-content-center w-100 download_inner_continaer align-items-center">
                                            <a class="download_file_in_course_content" href="{{ asset('storage/' . $swalef->content) }}"
                                                download>
                                                <div class="download_icon"><i class="fas fa-download"></i></div>
                                                <div class="download_text">تحميل الملف</div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p> {{ $swalef->content ?? '' }}</p>
                            @endif

                        </div>

                        {{-- <!-- reviews -->
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h3 class="place-details__sub-title mb-0">التقييمات</h3>
                            </div>
                            <ul class="place-details__reviews py-2 rates" style="max-height: 400px;overflow-y: scroll;">
                                <li class="d-flex flex-column flex-sm-row justify-content-between">
                                    <div class="d-flex flex-column flex-sm-row gap-lg">
                                        <div class="review-img">
                                            <img src="{{ asset('front_assets/imgs/empty.png') }}" alt="empty">
                                        </div>
                                        <div>
                                            <h4 class="review-author">محتوي اضافي</h4>
                                            <p class="review-text">محتوي اضافي</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <ul class="place-details__reviews py-2 rates">
                                <li id="empty" data-toggle="modal" data-target="#rating"
                                    style="margin-right: 240px;overflow-y:hidden;overflow-x:hidden"
                                    class="row justify-content-center">
                                    <div class="review-img col-12" style="margin-right: 100px;">
                                        <img src="{{ asset('front_assets/imgs/empty.png') }}" alt="empty">
                                    </div>
                                    <div class=" col-12 d-flex">
                                        <div>
                                            <p class="review-text mr-3">لا يوجد اي تقييمات حتي الان</p>
                                            <h4 class="review-author btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#rating"> كن اول من يضيف تقييم </h4>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <h2 class="page-title mb-2 mt-4"> السوالف الاكثر قراءة </h2>
            <div class="section-boxes container">
                <div class="section-boxes-wrap">
                    <div class="section-title">
                        <div class="section-projects-overflow">
                            <div class="card-row">
                                @foreach ($swalefs as $swalef)
                                    <div class="card-seation m-4">
                                        <a href="{{ url(app()->getLocale() . '/swalef/' . $swalef->id) }}">
                                            <div class="swiper-zoom-container">
                                                <!-- <span class="wishlist"><i class="fa-regular fa-heart"></i></span> -->
                                                <img
                                                    src="{{ asset($swalef->image ?? 'front_assets/imgs/our-services.jpg') }}">
                                                <span class="date-card"> <i
                                                        class="fa-solid fa-calendar-days p-2"></i>{{ $swalef->created_at ? date('d-m-Y', strtotime($swalef->created_at)) : '' }}</span>
                                            </div>
                                            <div class="title-area">
                                                <div class="project-type"><span>{{ $swalef->title ?? '' }}</span></div>
                                                <div class="project-title">
                                                    <span>{{ $swalef->description ? Str::substr($swalef->description, 0, 40) : '' }}</span>
                                                </div>
                                                <div class="arte-type d-flex justify-content-between">
                                                    <span
                                                        class="type-project"><span>{{ $swalef->active ? 'منشور' : 'غير منشور' }}</span></span>
                                                    {{-- <span class="rate-project">
                                                        <em class="rating-">
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                        </em>
                                                    </span> --}}
                                                </div>
                                            </div>
                                            <div class="see-more-n">
                                                <span class="see-m-nd">
                                                    شاهد المزيد
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('scripts')
    @parent
    @if ($swalef->type == 'file')
        <script>
            // If absolute URL from the remote server is provided, configure the CORS
            // header on that server.
            var url = '{{ $swalef->content }}';

            // Loaded via <script> tag, create shortcut to access PDF.js exports.
            var pdfjsLib = window['pdfjs-dist/build/pdf'];

            // The workerSrc property shall be specified.
            pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

            // Asynchronous download of PDF
            var loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = document.getElementById('the-canvas');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
                // PDF loading error
                console.error(reason);
            });
        </script>
    @endif
@endsection
