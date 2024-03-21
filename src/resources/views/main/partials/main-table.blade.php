<div class="l-container table-container js-table-container" id="discover-ranking">
    <div class="l-row layer5-no-margin" id="app">
        {{--{{ dd($industries) }}--}}
         <div class="table-container__inner u-p-18 u-full-height"
              id="js-main-table"
              data-url="{{ route('main.index-data') }}"
              data-channels="{{ $channels }}"
              data-industries="{{ $industries }}"
              data-periods="{{ $periods }}"
              data-locale="{{ app()->getLocale() }}"
         >

            <rank-table></rank-table>
        </div>

        <div class="l-container-flex u-p-30 u-hidden-s u-hidden-x text--white text--s">
            {{ __('messages.rank-table.no-organization-found-1') }} <a href="#" class="js-nav-item btn-link--red u-plr-5" data-id="contact-form"> {{ __('messages.rank-table.no-organization-found-cta') }} </a> {{ __('messages.rank-table.no-organization-found-2') }}
        </div>
    </div>
</div>