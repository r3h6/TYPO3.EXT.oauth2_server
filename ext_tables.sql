CREATE TABLE tx_oauth2server_domain_model_client (
	identifier varchar(255) DEFAULT '' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	secret varchar(255) DEFAULT '' NOT NULL,
	grant_type varchar(255) DEFAULT '' NOT NULL,
	redirect_uri text,
	is_confidential int(1) DEFAULT '0' NOT NULL,
);
