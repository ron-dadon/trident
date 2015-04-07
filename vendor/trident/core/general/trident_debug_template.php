<div class="panel panel-default" style="direction: ltr">
    <div class="panel-heading">
        <strong>Debug information</strong>
        <span class="pull-right" style="cursor: pointer"><a onclick="$('#debug-body').fadeToggle()">Toggle</a></span>
    </div>
    <div class="panel-body" id="debug-body">
        <p>
            <strong class="bg-primary">General Information:</strong>
            <ul class="list-inline">
                <li><strong>PHP version:</strong> {php-version}</li>
                <li><strong>Allocated memory:</strong> {aloc-memory}</li>
                <li><strong>Used memory:</strong> {used-memory}</li>
                <li><strong>Processing time:</strong> {process-time}</li>
            </ul>
        </p>
        <p>
            <strong class="bg-primary">Session information:</strong><br>
            {session}
        </p>
        <p>
            <strong class="bg-primary">Post information:</strong><br>
            {post}
        </p>
        <p>
            <strong class="bg-primary">Cookies information:</strong><br>
            {cookies}
        </p>
    </div>
</div>