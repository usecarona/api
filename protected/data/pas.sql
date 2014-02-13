CREATE TABLE tb_tokens (
    id_token SERIAL NOT NULL PRIMARY KEY,
    access_token VARCHAR NOT NULL,  
    token_type VARCHAR NOT NULL,
    refresh_token VARCHAR NOT NULL, 
    data_cadastro TIMESTAMP NOT NULL DEFAULT NOW(),
    expires_in INTEGER NOT NULL,
    client_id VARCHAR NOT NULL REFERENCES tb_sistemas(client_id),
    username VARCHAR REFERENCES tb_usuarios(login)
);

COMMENT ON COLUMN tb_tokens.access_token IS 'token access for data';
COMMENT ON COLUMN tb_tokens.token_type IS 'access token type provides the client with the information required to use it successfully';
COMMENT ON COLUMN tb_tokens.refresh_token IS 'credentials used to obtain access tokens';
COMMENT ON COLUMN tb_tokens.expires_in IS 'expires access token';
COMMENT ON COLUMN tb_tokens.client_id IS 'system identifier';
COMMENT ON COLUMN tb_tokens.username IS 'user identifier';

 