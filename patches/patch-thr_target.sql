ALTER TABLE /*_*/throttle_override ADD COLUMN thr_target varchar(255) NOT NULL DEFAULT "";
CREATE INDEX /*i*/thr_target ON /*_*/throttle_override (thr_target);
