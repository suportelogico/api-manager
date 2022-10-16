<?php

namespace SuporteLogico\ApiManager;

interface IController {
    const MTNIL    = 10000;
    const MTGET    = 10001;
    const MTPOST   = 10002;
    const MTPUT    = 10003;
    const MTPATCH  = 10003;
    const MTDELETE = 10004;
    const MTOPTIONS= 10005; 
}