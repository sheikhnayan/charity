{{-- Universal Component Renderer for Inner-Section Architecture --}}
@php
    $componentType = $component['type'] ?? '';
    $componentData = $component['data'] ?? [];
    $style = $component['style'] ?? [];
    $wrapperStyle = $component['wrapperStyle'] ?? [];
    
    // Generate style strings
    $styleStr = '';
    foreach ($style as $key => $value) {
        if ($value) {
            $styleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $key)) . ":$value;";
        }
    }
    
    $wrapperStyleStr = '';
    foreach ($wrapperStyle as $key => $value) {
        if ($value) {
            $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $key)) . ":$value;";
        }
    }
@endphp

<div class="component-wrapper" style="{{ $wrapperStyleStr }}">
    @switch($componentType)
        
        @case('heading')
            @php
                $level = $component['level'] ?? 'h2';
                $text = $component['html'] ?? 'Heading';
            @endphp
            <{{ $level }} style="{{ $styleStr }}">
                {!! $text !!}
            </{{ $level }}>
        @break

        @case('text')
            @php
                $text = $component['html'] ?? 'Text content';
            @endphp
            <div style="{{ $styleStr }}">
                {!! $text !!}
            </div>
        @break

        @case('button')
            @php
                $text = $component['html'] ?? 'Button';
                $href = $component['href'] ?? '#';
                $target = ($component['openInNewTab'] ?? false) ? '_blank' : '_self';
            @endphp
            <a href="{{ $href }}" target="{{ $target }}" class="btn" style="{{ $styleStr }}">
                {{ $text }}
            </a>
        @break

        @case('image')
            @php
                $imageData = $component['imageData'] ?? [];
                $src = $imageData['src'] ?? 'https://via.placeholder.com/400x250';
                $alt = $imageData['alt'] ?? 'Image';
                $width = $imageData['width'] ?? '100%';
                $height = $imageData['height'] ?? 'auto';
                $objectFit = $imageData['objectFit'] ?? 'cover';
                $link = $imageData['link'] ?? '';
                $openInNewTab = $imageData['openInNewTab'] ?? false;
            @endphp
            
            @if($link)
                <a href="{{ $link }}" {{ $openInNewTab ? 'target="_blank"' : '' }} style="display:inline-block;">
            @endif
            <img src="{{ $src }}" alt="{{ $alt }}" 
                 style="width:{{ $width }};height:{{ $height }};object-fit:{{ $objectFit }};border-radius:8px;{{ $styleStr }}" 
                 class="img-fluid"/>
            @if($link)
                </a>
            @endif
        @break

        @case('gallery')
            @php
                $galleryData = $component['galleryData'] ?? [];
                $images = $galleryData['images'] ?? [];
                $columns = $galleryData['columns'] ?? 3;
                $bootstrapClass = '';
                switch($columns) {
                    case 2: $bootstrapClass = 'col-md-6'; break;
                    case 3: $bootstrapClass = 'col-md-4'; break;
                    case 4: $bootstrapClass = 'col-md-3'; break;
                    case 5: $bootstrapClass = 'col-md-2'; break;
                    case 6: $bootstrapClass = 'col-md-2'; break;
                    default: $bootstrapClass = 'col-md-4';
                }
            @endphp
            <div class="row gallery-component" style="{{ $styleStr }}">
                @foreach($images as $image)
                    <div class="{{ $bootstrapClass }} mb-3">
                        <img src="{{ $image['src'] ?? 'https://via.placeholder.com/300x200' }}" 
                             alt="{{ $image['alt'] ?? 'Gallery Image' }}" 
                             class="img-fluid gallery-img-preview" 
                             style="width:100%;height:250px;object-fit:cover;border-radius:8px;cursor:pointer;">
                    </div>
                @endforeach
            </div>
        @break

        @case('slider')
            @php
                $sliderData = $component['sliderData'] ?? [];
                $images = $sliderData['images'] ?? [];
                $slidesToShow = $sliderData['slidesToShow'] ?? 1;
                $slideSpeed = $sliderData['slideSpeed'] ?? 2000;
                $sliderId = 'slider-' . ($componentId ?? uniqid());
            @endphp
            <div class="owl-carousel owl-theme" id="{{ $sliderId }}" style="{{ $styleStr }}">
                @foreach($images as $image)
                    <div class="item">
                        <img src="{{ $image['src'] ?? 'https://via.placeholder.com/800x400' }}" 
                             alt="{{ $image['alt'] ?? 'Slider Image' }}" 
                             style="width:100%;height:400px;object-fit:cover;border-radius:8px;">
                    </div>
                @endforeach
            </div>
            <script>
                $(document).ready(function(){
                    $("#{{ $sliderId }}").owlCarousel({
                        items: {{ $slidesToShow }},
                        loop: true,
                        margin: 10,
                        autoplay: true,
                        autoplayTimeout: {{ $slideSpeed }},
                        responsive: {
                            0: { items: 1 },
                            600: { items: {{ min(2, $slidesToShow) }} },
                            1000: { items: {{ $slidesToShow }} }
                        }
                    });
                });
            </script>
        @break

        @case('custom-form')
            @php
                $formFields = $component['customFormFields'] ?? [];
            @endphp
            <form class="custom-form" style="{{ $styleStr }}">
                @foreach($formFields as $field)
                    <div class="mb-3">
                        <label class="form-label">{{ $field['label'] ?? 'Field' }}</label>
                        @switch($field['type'] ?? 'text')
                            @case('textarea')
                                <textarea class="form-control" name="{{ $field['name'] ?? 'field' }}" 
                                          {{ ($field['required'] ?? false) ? 'required' : '' }}></textarea>
                            @break
                            @case('select')
                                <select class="form-control" name="{{ $field['name'] ?? 'field' }}" 
                                        {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                    @foreach($field['options'] ?? [] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @break
                            @default
                                <input type="{{ $field['type'] ?? 'text' }}" class="form-control" 
                                       name="{{ $field['name'] ?? 'field' }}" 
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                        @endswitch
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        @break

        @case('divider')
            @php
                $height = $style['height'] ?? '2px';
                $backgroundColor = $style['backgroundColor'] ?? '#ddd';
            @endphp
            <hr style="height:{{ $height }};background-color:{{ $backgroundColor }};border:none;{{ $styleStr }}">
        @break

        @case('spacer')
            @php
                $height = $style['height'] ?? '20px';
            @endphp
            <div style="height:{{ $height }};{{ $styleStr }}"></div>
        @break

        @case('custom-banner')
            @php
                $banner = $component['customBannerData'] ?? [];
            @endphp
            <div style="position:relative; text-align:{{ $banner['textAlign'] ?? 'center' }};{{ $styleStr }}">
                @if(!empty($banner['imgSrc']))
                    <img src="{{ $banner['imgSrc'] }}" style="width:100%;height:auto;">
                @endif
                @if(!empty($banner['title']))
                    <h3 style="position:absolute; top:40%; left:50%; transform:translate(-50%,-50%);
                        color:{{ $banner['titleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['titleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['titleFontSize'] ?? '2em' }}; width: 90%;
                        text-align:{{ $banner['textAlign'] ?? 'center' }};" class="custom-banner-title">
                        {{ $banner['title'] }}
                    </h3>
                @endif
                @if(!empty($banner['subtitle']))
                    <p style="position:absolute; top:55%; left:50%; transform:translate(-50%,-50%);
                        color:{{ $banner['subtitleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['subtitleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['subtitleFontSize'] ?? '1.2em' }}; width: 90%;
                        text-align:{{ $banner['textAlign'] ?? 'center' }};
                        margin-top: {{ $banner['subtitleMarginTop'] ?? '0px' }}">
                        {{ $banner['subtitle'] }}
                    </p>
                @endif
            </div>
        @break

        @case('custom-banner')
            @php
                $banner = $component['customBannerData'] ?? [];
            @endphp
            <div style="position:relative; text-align:{{ $banner['textAlign'] ?? 'center' }};{{ $styleStr }}">
                @if(!empty($banner['imgSrc']))
                    <img src="{{ $banner['imgSrc'] }}" style="width:100%;height:auto;">
                @endif
                @if(!empty($banner['title']))
                    <h3 style="position:absolute; top:40%; left:50%; transform:translate(-50%,-50%);
                        color:{{ $banner['titleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['titleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['titleFontSize'] ?? '2em' }}; width: 90%;
                        text-align:{{ $banner['textAlign'] ?? 'center' }};" class="custom-banner-title">
                        {{ $banner['title'] }}
                    </h3>
                @endif
                @if(!empty($banner['subtitle']))
                    <p style="position:absolute; top:55%; left:50%; transform:translate(-50%,-50%);
                        color:{{ $banner['subtitleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['subtitleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['subtitleFontSize'] ?? '1.2em' }}; width: 90%;
                        text-align:{{ $banner['textAlign'] ?? 'center' }};
                        margin-top: {{ $banner['subtitleMarginTop'] ?? '0px' }}">
                        {{ $banner['subtitle'] }}
                    </p>
                @endif
            </div>
        @break

        @case('donor-list')
            <div style="{{ $styleStr }}">
                <div class="col-12 mt-4 donor-list-component">
                    <div class="col-12 mt-4">
                        <div id="studentTable_wrapper" class="dataTables_wrapper no-footer">
                            <table id="studentTable" class="display table dataTable no-footer" role="grid">
                                <tbody>
                                    @php
                                        $domain = request()->getHttpHost();
                                        $website = \App\Models\Website::where('domain', $domain)->first();
                                    @endphp
                                    @if($website)
                                        @php
                                            $donations = \App\Models\Donation::where('website_id', $website->id)->with('user')->where('status', 1)->get();
                                        @endphp
                                        @if($donations && count($donations) > 0)
                                            @foreach($donations as $donation)
                                                <tr class="grid" role="row">
                                                    <td class="grid-data">
                                                        <div class="non-float">
                                                            <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">{{ $donation->amount }}</p>
                                                            <p style="color: #f4f3f0; font-size: 12px; margin-bottom: 0;">
                                                                @php
                                                                    $firstName = $donation->user->name ?? 'Anonymous';
                                                                    $words = explode(' ', $firstName);
                                                                    $displayName = $words[0];
                                                                    if (isset($words[1])) {
                                                                        $displayName .= ' ' . substr($words[1], 0, 1) . '.';
                                                                    }
                                                                    echo $displayName;
                                                                @endphp
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="grid" role="row">
                                                <td class="grid-data">
                                                    <div class="non-float">
                                                        <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">No donations found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr class="grid" role="row">
                                            <td class="grid-data">
                                                <div class="non-float">
                                                    <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">Website not found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @break

        @case('faq')
            <div style="{{ $styleStr }}">
                @php
                    $domain = request()->getHttpHost();
                    $website = \App\Models\Website::where('domain', $domain)->first();
                @endphp
                @if($website)
                    @php
                        $faqs = \App\Models\Page::where('website_id', $website->id)->where('name', 'FAQ')->first();
                    @endphp
                    @if($faqs && $faqs->state)
                        @php
                            $faqState = is_string($faqs->state) ? json_decode($faqs->state, true) : $faqs->state;
                        @endphp
                        @if(is_array($faqState))
                            <div class="accordion" id="faqAccordion">
                                @foreach($faqState as $faqIndex => $faq)
                                    @if(isset($faq['type']) && $faq['type'] === 'text')
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $faqIndex }}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faqIndex }}" aria-expanded="false" aria-controls="collapse{{ $faqIndex }}">
                                                    {{ strip_tags($faq['html'] ?? "Question $faqIndex") }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $faqIndex }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $faqIndex }}" data-bs-parent="#faqAccordion">
                                                <div class="accordion-body">
                                                    {!! $faq['html'] ?? '' !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @else
                        <p>No FAQ content found.</p>
                    @endif
                @else
                    <p>Website not found.</p>
                @endif
            </div>
        @break

        @case('invest-cta')
            @include('admin.page.page-components.invest-cta', ['component' => $component])
        @break

        @default
            {{-- Fallback for any unhandled component types --}}
            <div style="{{ $styleStr }}">
                @if(isset($component['html']))
                    {!! $component['html'] !!}
                @else
                    <p>Component: {{ $componentType }}</p>
                @endif
            </div>
    @endswitch
</div>
