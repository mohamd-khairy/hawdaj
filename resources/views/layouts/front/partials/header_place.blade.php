 <!-- navbar -->
 <div class="navbar-wrapper">
     <div class="navbar-wrapper__bg"></div>
     <div class="container">

         @include('layouts.front.partials.nav')



         @if (isset($zad) || isset($zads))
             @php
                 $type = 'زاد الجادل';
                 $link = 'zads';
             @endphp
         @elseif(isset($stores) || isset($store))
             @php
                 $type = 'المتاجر';
                 $link = 'stores';
             @endphp
         @else
             @php
                 $type = 'الأماكن';
                 $link = 'places';
             @endphp
         @endif

         @if (!isset($map_most_pupular_places))
             <div class="breadcrumb__container">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="/">الرئيسية</a></li>
                         @if (isset($places) && $places)
                             <li class="breadcrumb-item {{ isset($place) ? '' : 'active' }}"><a
                                     href="/{{ $link ?? 'places' }}">{{ $type ?? '' }}</a></li>
                             @if (isset($place))
                                 <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($place->title) ? substr($place->title, 0, 20) : '' }}
                                 </li>
                             @endif
                         @endif
                     </ol>
                 </nav>
                 
                 @if (isset($place->title))
                 <h1 class="page-title">{{ isset($place->title) ? substr($place->title, 0, 20) : $type }}</h1>
                 @endif

             </div>
         @endif
     </div>
 </div> <!-- navbar -->
