    <div class="container">
        <div class="panel panel-default" style="direction: ltr">
            <div class="panel-heading">
                <strong><i class="fa fa-bug"></i> Debug information</strong>
                <span class="pull-right" style="cursor: pointer"><a onclick="$('#debug-body').fadeToggle()">Toggle</a></span>
            </div>
            <div class="panel-body" id="debug-body">
                <p class="bg-primary">
                    <strong>General Information:</strong>
                    <ul class="list-inline">
                        <li><strong>PHP version:</strong> {php-version}</li>
                        <li><strong>Allocated memory:</strong> {alloc-memory}</li>
                        <li><strong>Used memory:</strong> {used-memory}</li>
                        <li><strong>Processing time:</strong> {process-time}</li>
                        <li><strong>Client address:</strong> {client-ip}</li>
                        <li><strong>Request URI:</strong> {request-uri}</li>
                        <li><strong>Client system:</strong> {client-system}</li>
                    </ul>
                </p>
                <p class="bg-primary">
                    <strong>Session information:</strong><br>
                    {session}
                </p>
                <p class="bg-primary">
                    <strong>Post information:</strong><br>
                    {post}
                </p>
                <p class="bg-primary">
                    <strong>Cookies information:</strong><br>
                    {cookies}
                </p>
            </div>
        </div>
    </div>