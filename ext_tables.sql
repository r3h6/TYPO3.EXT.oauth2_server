CREATE TABLE tx_oauth2server_domain_model_authcode (

	identifier varchar(255) DEFAULT '' NOT NULL,
	expires_at int(11) DEFAULT '0' NOT NULL,
	user varchar(255) DEFAULT '' NOT NULL,
	scopes text,
	client varchar(255) DEFAULT '' NOT NULL,
	revoked smallint(5) unsigned DEFAULT '0' NOT NULL

);

CREATE TABLE tx_oauth2server_domain_model_client (

	rowDescription text,
	identifier varchar(36) DEFAULT '' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	secret varchar(255) DEFAULT '' NOT NULL,
	grant_type varchar(255) DEFAULT '' NOT NULL,
	redirect_uri text,
	is_confidential smallint(5) unsigned DEFAULT '0' NOT NULL,
	skip_consent smallint(5) unsigned DEFAULT '0' NOT NULL,
	allowed_scopes text

);

CREATE TABLE tx_oauth2server_domain_model_refreshtoken (

	identifier varchar(255) DEFAULT '' NOT NULL,
	expires_at int(11) DEFAULT '0' NOT NULL,
	access_token varchar(255) DEFAULT '' NOT NULL,
	revoked smallint(5) unsigned DEFAULT '0' NOT NULL

);

CREATE TABLE tx_oauth2server_domain_model_accesstoken (

	identifier varchar(255) DEFAULT '' NOT NULL,
	expires_at int(11) DEFAULT '0' NOT NULL,
	user varchar(255) DEFAULT '' NOT NULL,
	scopes text,
	client varchar(255) DEFAULT '' NOT NULL,
	revoked smallint(5) unsigned DEFAULT '0' NOT NULL

);
