<h3 class="page-header">Application Log Files</h3>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <table class="table table-condensed">
                <thead>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Size</th>
                    <th>Time</th>
                    <th>{{ trans('app.action') }}</th>
                </thead>
                <tbody>
                    @forelse($logFiles as $key => $logFile)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $logFile->getFilename() }}</td>
                        <td>{{ $logFile->getSize() }}</td>
                        <td>{{ date('Y-m-d H:i:s', $logFile->getMTime()) }}</td>
                        <td>
                            <a href="{{ route('log-files.show', $logFile->getFilename()) }}" title="Show file {{ $logFile->getFilename() }}">View</a> |
                            <a href="{{ route('log-files.download', $logFile->getFilename()) }}" title="Download file {{ $logFile->getFilename() }}">Download</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">No Log File Exists</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
