DROP PROCEDURE IF EXISTS update_zero_vagas;

CREATE PROCEDURE update_zero_vagas()
   UPDATE sim.pacotes
   SET estado = 3
   WHERE vagas = 0;



SET GLOBAL event_scheduler = ON;
CREATE EVENT IF NOT EXISTS e_hourly
ON SCHEDULE EVERY 1 HOUR
DO
   CALL update_zero_vagas();

CALL update_zero_vagas();
SELECT * FROM sim.pacotes;