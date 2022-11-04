<?php

namespace SuporteLogico\ApiManager;

interface IController {
    const CT_APPLICATION_JSON         = 10000; //application/json
    const CT_APPLICATION_OCTET_STREAM = 10001; //application/octet/stream
    const CT_TEXT_HTML                = 10002; //text/html
    const CT_TEXT_PLAIN               = 10003; //text/plain

}