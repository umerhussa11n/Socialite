<table id="calendar_monthly" class="calendar_monthly calendar monthly_calendar ohidden" style="display: grid; visibility: hidden;">
            <tr>
                <td style="min-height: auto;display: table-cell; background-color:green; color: white; text-align:center;font-weight:bold;" colspan="7">
                    Monthly
                </td>
            </tr>
            <tr class="days">
                    <?php
                    for($i = 1; $i <= 31 ; $i++)
                    {
                    ?>
                    <td class="day d_<?php echo $i; ?>">
                        <div class="month-day"><?php echo $i; ?></div>
                    </td>
                    <?php
                    }
                    ?>


            </tr>
</table>