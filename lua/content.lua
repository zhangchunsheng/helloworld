ngx.header.content_type = "text/plain";

local redis = require "resty.redis";
local config = ngx.shared.config;

local instance = redis:new();

local host = config:get("host");
local port = config:get("port");

local ok, err = instance:connect(host, port);
if not ok then
    ngx.log(ngx.ERR, err);
    ngx.exit(ngx.HTTP_SERVICE_UNAVAILABLE);
end

instance:set("name", "peter");

local name = instance:get("name");
instance:close();

ngx.say("name: ", name);
