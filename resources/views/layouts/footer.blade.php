<footer class="footer">
    <form id="lang" action="/localization" method="POST">
        {{ csrf_field() }}
        <div class="navbar-inner">
            <div class="container">
                @lang('localization.lang'):
                <select name="locale" id="locale" onchange="this.form.submit()">
                    @foreach (__('localization.name') as $code => $name)
                        <option value="{{ $code }}"
                            @if (App::getLocale() == $code)
                                selected
                            @endif
                        >{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</footer>
