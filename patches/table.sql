-- Table of exemptions from account creation throttle
CREATE TABLE /*_*/throttle_override (
	-- Primary key for accessing specific exemptions
	thr_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,

	-- Throttle override target
	thr_target VARCHAR(255) NOT NULL,

	-- Type of the throttles being overriden
	thr_type SET( 'actcreate', 'edit', 'move', 'mailpassword', 'emailuser' ) NOT NULL,

	-- Start of the exempt range
	thr_range_start TINYBLOB NOT NULL,

	-- End of the exempt range
	thr_range_end TINYBLOB NOT NULL,

	-- Timestamp when the exemption expires
	thr_expiry VARBINARY(14) NOT NULL,

	-- Reason for the exemption
	thr_reason TINYBLOB
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/thr_target ON /*_*/throttle_override (thr_target);
CREATE INDEX /*i*/thr_range ON /*_*/throttle_override (thr_range_start(8), thr_range_end(8));
CREATE INDEX /*i*/thr_expiry ON /*_*/throttle_override (thr_expiry);
