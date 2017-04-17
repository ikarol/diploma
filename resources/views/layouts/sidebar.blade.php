<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>@lang('diplomas.sidebar.title')</h1>
        </div>
        <div class="panel-body">
            <ul>
                @foreach (&$archives as $stats)
                    <li>
                        <a
                            href="/diplomas?month={{
                                $stats['month'] =
                                DateTime::createFromFormat('!m', $stats['month'])
                                    ->format('F')
                            }}&year={{
                                $stats['year']
                            }}">
                                {{ $stats['month'] }} {{ $stats['year'] }}
                        </a>
                    </li>
                @endforeach
                    <li>
                        <a href="/diplomas">@lang('diplomas.sidebar.all')</a>
                    </li>
            </ul>
        </div>
    </div>
</div>
