-- Run once. Adds references/snapshot support for all three print types.
ALTER TABLE tbl_prints
    ADD COLUMN prtCartID INT(10) NULL AFTER prtType,
    ADD COLUMN prtData LONGTEXT NULL AFTER prtKitchenID,
    MODIFY COLUMN prtKitchenID INT(10) NULL;

-- prtType=1: prtCartID stores tbl_orders.odrID (payment receipt).
-- prtType=2: prtCartID stores the first tbl_cart.ctID; prtData stores a complete
--            snapshot of the table cart so it remains printable after checkout.
-- prtType=3: prtKitchenID stores tbl_kitchen.kitID (kitchen ticket).
-- prtStatus=0 queued, prtStatus=1 sent successfully to the printer.
