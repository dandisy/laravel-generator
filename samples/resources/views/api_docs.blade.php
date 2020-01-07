<body style="padding:15px">
<h3>REST API</h3>
<hr>
<div>
    <p>GET :</p>
    <p>{{url('/')}}/api/boards/1</p>
</div>
<hr>
<div>
    <p>POST :</p>
    <p>{{url('/')}}/api/histories?model_type=App\Models\Quest&model_id=1&user_id=1</p>
</div>
<hr>
<div>
    <p>POST :</p>
    <p>{{url('/')}}/api/histories?model_type=App\Models\Redeem&model_id=1&user_id=1</p>
</div>
<hr>
<div>
    <p>POST :</p>
    <p>{{url('/')}}/api/upload</p>
    <p>Request :</p>
    <p>
        <pre>
            {
                "file" : "{input file or base64 encoded file}",
                "extention" : "[jpg|jpeg|png|pdf]",
                "type" : "discussion",
                "user_id" : "{Logged in user id}"
            }
        </pre>
    </p>
    <p>Response :</p>
    <p>
        <pre>
            {
                "status": true,
                "message": "file saved successfully",
                "data": "http://{your-domain}/storage/uploads/1/1_discussion_1575440737.jpg"
            }
        </pre>
    </p>
</div>

<hr>
<h3>ELOREST API</h3>
<hr>
<div>
    <p>GET :</p>
    <p>{{url('/')}}/api/elorest/Models/Quest</p>
</div>
<hr>
<div>
    <p>GET :</p>
    <p>{{url('/')}}/api/elorest/Models/Redeem</p>
</div>
<hr>
<div>
    <p>GET :</p>
    <p>{{url('/')}}/api/elorest/Models/History</p>
</div>
</body>