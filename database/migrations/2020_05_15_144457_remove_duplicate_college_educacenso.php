<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateCollegeEducacenso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            DELETE FROM Modules.educacenso_ies
            WHERE ies_id IN (
                5812, 5813, 5815, 5816, 4539, 3316, 1143, 5007, 5353, 14236, 3461, 3474, 3484, 4994, 4930,
                5435, 5031, 5293, 5487, 5364, 5528, 5354, 5397, 5497, 5486, 4643, 5094, 5357, 4972, 5526,
                3500, 5493, 5060, 5033, 5360, 5265, 3249, 5390, 2622, 5181, 5564, 1589, 16107, 5437, 5540,
                5375, 5445, 5532, 4287, 5504, 5522, 5418, 5499, 2453, 5547, 1802, 5525, 5546, 5425, 3621,
                3622, 4866, 5584, 4864, 3248, 4176, 4939, 14189, 14191, 3361, 5250, 4159, 5006, 3251, 5067,
                5299, 5011, 2010, 3871, 4608, 811, 4233, 4667, 2686, 3711, 3326, 3683, 2106, 4961, 5152,
                4973, 5150, 21583, 5132, 3982, 1715, 5126, 3508, 4561, 3277, 5143, 2083, 4089, 5278, 3124,
                5567, 5596, 5235, 2701, 5510, 4862, 2250, 3660, 5149, 5583, 4216, 1389, 3265, 4125, 4861,
                5416, 4638, 3273, 1408, 4885, 3212, 2315, 4886, 3310, 3747, 3571, 3037, 5471, 3269, 4105,
                2705, 5457, 3289, 5624, 2504, 4078, 4364, 4076, 4245, 3296, 5255, 3457, 5203, 3763, 5595,
                14237, 14199, 3565, 3629, 3653, 5590, 2610, 1181, 4160, 4244, 3949, 3964, 4882, 4225, 4881,
                4624, 3315, 14005, 4991, 1566, 1635, 3988, 436, 6490, 3401, 5058, 15682, 5351, 14367, 4328,
                5279, 4783, 4448, 5597, 4180, 5140, 5563, 4074, 3370, 4905, 22022, 2061, 18049, 5089, 4215,
                4937, 5436, 11817, 21492, 5226, 486, 14201, 5463, 5410, 5414, 16881, 5588, 3198, 17952, 5551, 1338
            );
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
