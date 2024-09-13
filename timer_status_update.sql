DROP PROCEDURE IF EXISTS update_state_after_start;

CREATE PROCEDURE update_state_after_start()

   UPDATE sim.pacotes
   SET estado = 1
   WHERE inicio < NOW() AND estado != 1;


DROP PROCEDURE IF EXISTS update_state_after_end;

CREATE PROCEDURE update_state_after_end()

   UPDATE sim.pacotes
   SET estado = 2
   WHERE termino < NOW() AND estado != 2;



SET GLOBAL event_scheduler = ON;

CREATE EVENT IF NOT EXISTS e_hourly_update_start
ON SCHEDULE EVERY 1 HOUR
DO
   CALL update_state_after_start();

CREATE EVENT IF NOT EXISTS e_hourly_update_end
ON SCHEDULE EVERY 1 HOUR
DO
   CALL update_state_after_end();
