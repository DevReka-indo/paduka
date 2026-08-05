<?php

namespace Database\Seeders;

use App\Models\QcFacility;
use App\Models\QcFacilityCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class QcFacilitySeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan kategori tersedia
        |--------------------------------------------------------------------------
        */

        $this->call(QcFacilityCategorySeeder::class);

        $categoryIds = QcFacilityCategory::query()
            ->pluck('id', 'slug');

        $requiredCategories = [
            'alat-ukur',
            'alat-uji',
            'alat-kalibrasi',
            'alat-elektrik',
            'alat-mekanik',
            'alat-force',
            'alat-geometri',
        ];

        foreach ($requiredCategories as $categorySlug) {
            if (!$categoryIds->has($categorySlug)) {
                throw new RuntimeException(
                    "Kategori fasilitas dengan slug {$categorySlug} tidak ditemukan."
                );
            }
        }

        $specification = static function (array $lines): string {
            return implode(PHP_EOL, $lines);
        };

        /*
        |--------------------------------------------------------------------------
        | Data fasilitas berdasarkan katalog
        |--------------------------------------------------------------------------
        */

        $facilities = [
            [
                'category_slug' => 'alat-uji',
                'name' => 'Temperature & Humidity Chamber',
                'brand' => 'ASLI',
                'model' => 'TH-1000-D',
                'technical_specifications' => $specification([
                    'Temperature range: -40°C hingga 150°C',
                    'Humidity range: 20% hingga 98% RH',
                    'Resolution: 0.1°C, 0.1% RH',
                    'Uniformity temperature: ±2.0°C',
                    'Uniformity humidity: ±3.0% RH',
                    'Control accuracy temperature: ±0.5°C',
                    'Control accuracy humidity: ±2.5% RH',
                    'Temperature rising approx: 0.1 hingga 3.0°C/min',
                    'Temperature falling approx: 0.1 hingga 1.5°C/min',
                ]),
            ],
            [
                'category_slug' => 'alat-uji',
                'name' => 'Vibration Shaker',
                'brand' => 'ASLI',
                'model' => 'ES-3',
                'technical_specifications' => $specification([
                    'Rated sine force: 3000 N',
                    'Rated random force: 3000 N',
                    'Frequency range: 3 hingga 3500 Hz',
                    'Rated acceleration: 1000 m/s²',
                    'Rated speed: 1.6 m/s',
                    'Rated replacement: 25 mm',
                    'Maximum loading: 100 kg',
                    'Moving coil: 3.5 kg',
                    'Dimension of moving coil: Φ150',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Surge Generator',
                'brand' => 'LISUN',
                'model' => 'SG61000-5',
                'technical_specifications' => $specification([
                    'Output voltage open: 1.2/50 µs ±20%',
                    'Output current short: 8/20 µs ±20%',
                    'Output impedance: 2 Ω dan 12 Ω',
                    'Output voltage range: 0 hingga 6 kV ±10%',
                    'Output current range: 0 hingga 3 kV ±10%',
                    'Surge repetition: 1 hingga 9999 kali',
                    'Interval time: 20 hingga 9999 detik',
                    'Dimensions: 44 × 45 × 35 cm',
                    'Gross weight: 30 kg',
                ]),
            ],
            [
                'category_slug' => 'alat-force',
                'name' => 'Terminal Pull-Off Tester',
                'brand' => 'Kingsing',
                'model' => 'KS-A520',
                'technical_specifications' => $specification([
                    'Force range: 500 N',
                    'Optional tester: 1000 N',
                    'Resolution: 0.01 N',
                    'Accuracy: ±0.5%',
                    'Voltage: 220 V / 50 Hz',
                    'Ambient temperature: 0 hingga 60°C',
                    'Ambient humidity: kurang dari 80%',
                    'Allowable overload: 150%',
                    'Dimensions: 470 × 200 × 300 mm',
                ]),
            ],
            [
                'category_slug' => 'alat-force',
                'name' => 'Tensile Test Machine',
                'brand' => null,
                'model' => null,
                'technical_specifications' => $specification([
                    'Capacity: 1000 kg',
                    'Resolution: 1/100000',
                    'Accuracy: ±0.1%',
                    'Test speed: 0.01 hingga 1000 mm/min',
                    'Test stroke: 1000 mm',
                    'Test width depan dan belakang: 120 mm',
                    'Test width kanan dan kiri: tidak terbatas',
                    'Dimensions: 630 × 500 × 1600 mm',
                    'Power: AC 200 V, 10 A, 50/60 Hz',
                ]),
            ],
            [
                'category_slug' => 'alat-kalibrasi',
                'name' => 'Multifunction Calibrator',
                'brand' => 'Tunkia',
                'model' => 'TD1855',
                'technical_specifications' => $specification([
                    'DC voltage output: 20 mV hingga 1100 V',
                    'DC current output: 2 µA hingga 22 A',
                    'AC voltage output: 20 mV hingga 1100 V',
                    'AC current output: 200 µA hingga 22 A',
                    'Frequency: 45 Hz hingga 1100 Hz',
                    'Resistance output: 10 Ω hingga 220 MΩ',
                    'Frequency output: 1 Hz hingga 2 MHz',
                ]),
            ],
            [
                'category_slug' => 'alat-kalibrasi',
                'name' => 'Current Coil',
                'brand' => 'Tunkia',
                'model' => 'TD1020',
                'technical_specifications' => $specification([
                    'Current output: 1000 A hingga 2000 A',
                    'Maximum current input: 2 A hingga 44 A',
                    'DC accuracy: 0.3%',
                    'AC accuracy: 0.3% pada 50 Hz',
                    'AC accuracy: 0.5% pada 400 Hz',
                    'Frequency: 45 Hz hingga 400 Hz',
                ]),
            ],
            [
                'category_slug' => 'alat-kalibrasi',
                'name' => 'Test Weight',
                'brand' => null,
                'model' => 'Class M2',
                'technical_specifications' => $specification([
                    'Class: M2',
                    'Available weight: 20 kg',
                    'Available weight: 10 kg',
                    'Available weight: 5 kg',
                    'Available weight: 2 kg',
                    'Available weight: 1 kg',
                ]),
            ],
            [
                'category_slug' => 'alat-geometri',
                'name' => 'Linear Scale Calibrator',
                'brand' => 'Ingram',
                'model' => 'SK105R',
                'serial_number' => '202111N287',
                'technical_specifications' => $specification([
                    'Measurement range: 1200 mm',
                ]),
            ],
            [
                'category_slug' => 'alat-force',
                'name' => 'Torque Wrench Tester',
                'brand' => 'ALIYIQI',
                'model' => 'ANJ-M200',
                'technical_specifications' => $specification([
                    'Measurement range: 0 hingga 200.00 N·m',
                    'Resolution: 0.01 N·m',
                    'Measurement range: 0 hingga 2042 kg·cm',
                    'Resolution: 0.1 kg·cm',
                    'Measurement range: 0 hingga 17772.4 lb·in',
                    'Resolution: 0.1 lb·in',
                    'Accuracy: ±1%',
                    'Frequency: 50 hingga 60 Hz',
                    'Voltage: 110 hingga 240 V AC',
                    'Weight: 65 kg',
                ]),
            ],
            [
                'category_slug' => 'alat-force',
                'name' => 'Torque Tester Digital',
                'brand' => 'Cedar',
                'model' => 'CD-100M',
                'technical_specifications' => $specification([
                    'Measurement range: 0.10 hingga 10.00 N·m',
                    'Accuracy: ±0.5%',
                    'Display: LCD digital 3.5 inci',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Multimeter',
                'brand' => 'Fluke',
                'model' => '87V',
                'technical_specifications' => $specification([
                    'DC voltage: 0.1 mV hingga 1000 V',
                    'AC voltage: 0.1 mV hingga 1000 V',
                    'DC current: 0.1 µA hingga 10 A',
                    'AC current: 0.1 µA hingga 10 A',
                    'Resistance: 0.1 Ω hingga 50 MΩ',
                    'Capacitance: 0.01 nF hingga 9999 µF',
                    'Frequency: 0.5 Hz hingga 199.99 kHz',
                    'Duty cycle: 99.9%',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Clamp Meter',
                'brand' => 'Weidmuller',
                'model' => '2602',
                'technical_specifications' => $specification([
                    'DC voltage: 200 mV DC hingga 600 V DC',
                    'AC voltage: 200 mV AC hingga 600 V AC',
                    'AC current: 20 A AC hingga 1000 A AC',
                    'Resistance: 200 Ω hingga 20 MΩ',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Clamp Meter',
                'brand' => 'Fluke',
                'model' => '375',
                'technical_specifications' => $specification([
                    'DC voltage: 600 V DC',
                    'AC voltage: 600 V AC',
                    'DC current: 600 A DC',
                    'AC current: 600 A AC',
                    'Resistance: 60 Ω',
                    'Capacitance: 1 µF hingga 1000 µF',
                    'Frequency: 500 Hz',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Clamp Meter',
                'brand' => 'Fluke',
                'model' => '317',
                'technical_specifications' => $specification([
                    'DC voltage: 600 V DC',
                    'AC voltage: 600 V AC',
                    'DC current: 40 A DC dan 600 A DC',
                    'AC current: 40 A AC dan 600 A AC',
                    'Resistance: 400 Ω dan 4000 Ω',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Insulation Tester Digital',
                'brand' => 'Hioki',
                'model' => 'IR4056',
                'technical_specifications' => $specification([
                    'Output voltage: 50 V DC',
                    'Output voltage: 125 V DC',
                    'Output voltage: 250 V DC',
                    'Output voltage: 500 V DC',
                    'Output voltage: 1000 V DC',
                    'Maximum resistance: 100 MΩ hingga 4000 MΩ',
                    'Minimum resistance: 0.05 MΩ hingga 1 MΩ',
                    'DC voltage range: 40.2 V DC hingga 600 V DC',
                    'AC voltage range: 420 V AC hingga 600 V AC',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Insulation Tester Analog',
                'brand' => 'Hioki',
                'model' => '3490',
                'technical_specifications' => $specification([
                    'Output voltage: 250 V DC',
                    'Output voltage: 500 V DC',
                    'Output voltage: 1000 V DC',
                    'Maximum resistance: 100 MΩ, 100 MΩ, dan 4000 MΩ',
                    'Minimum resistance: 0.25 MΩ, 0.5 MΩ, dan 1 MΩ',
                    'AC voltage range: 0 V AC hingga 600 V AC',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Insulation Tester Digital',
                'brand' => 'Kyoritsu',
                'model' => '3005A',
                'technical_specifications' => $specification([
                    'Test voltage: 250 V, 500 V, dan 1000 V',
                    'Insulation measuring range: 20 MΩ',
                    'Insulation measuring range: 200 MΩ',
                    'Insulation measuring range: 2000 MΩ',
                    'Nominal current: minimum 1 mA DC',
                    'Continuity measuring range: 20 Ω',
                    'Continuity measuring range: 200 Ω',
                    'Continuity measuring range: 2000 Ω',
                    'Continuity measuring current: minimum 200 mA DC',
                    'AC voltage range: 0 hingga 600 V AC',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Phase Sequence Tester',
                'brand' => 'Kyoritsu',
                'model' => 'KEW 8031F',
                'technical_specifications' => $specification([
                    'Operational voltage: 110 V AC hingga 600 V DC',
                    'Fuse: 0.5 A / 600 V',
                    'Continuous use time limit: kurang dari 500 V',
                    'Frequency response: 50/60 Hz',
                    'Cord length: 1.3 m',
                    'Cord identification: R merah, S putih, T biru',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'AC Leakage Clamp Meter',
                'brand' => 'Hioki',
                'model' => 'CM4002',
                'technical_specifications' => $specification([
                    'AC current range: 6 A, 60 mA, 600 mA, 6 A, 60 A, dan 200 A',
                    'AC voltage range: N/A',
                    'Frequency range: 15 Hz hingga 2000 Hz',
                    'Crest factor: 3 selain range 200 A',
                    'Crest factor: 1.5 pada range 200 A',
                    'Filter cut-off frequency: 180 Hz ±30 Hz pada filter ON',
                ]),
            ],
            [
                'category_slug' => 'alat-ukur',
                'name' => 'Sound Level Meter',
                'brand' => 'Center',
                'model' => '322',
                'technical_specifications' => $specification([
                    'Low level range: 30 hingga 80 dB',
                    'Medium level range: 50 hingga 100 dB',
                    'High level range: 80 hingga 130 dB',
                    'Accuracy: ±1.5 dB',
                    'Frequency weighting: A/C',
                    'Dynamic range: 50 dB',
                    'Frequency range: 31.5 Hz hingga 8 kHz',
                    'Microphone: Electret Condenser Microphone',
                    'Auxiliary output: AC/DC output',
                    'Battery: 9 V',
                ]),
            ],
            [
                'category_slug' => 'alat-ukur',
                'name' => 'Lux Meter',
                'brand' => 'Kyoritsu',
                'model' => '5202',
                'technical_specifications' => $specification([
                    'Measurement range: 0.1 hingga 19990 lux',
                    'Accuracy range 200: ±4% reading ±5 digit',
                    'Accuracy range 2000: ±4% reading ±5 digit',
                    'Accuracy range 20000: ±5% reading ±4 digit',
                    'Current consumption: approximately 2 mA',
                    'Response time: 2.5 times per second',
                    'Operating temperature: 0 hingga 50°C',
                    'Storage temperature: -10 hingga 60°C',
                    'Power source: 6F22 9 V × 1',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Cable Tester dan Wire Tracker',
                'brand' => null,
                'model' => null,
                'technical_specifications' => $specification([
                    'Certification: CE dan UKCA',
                    'Transmitter: port flashing',
                    'Transmitter: wire tracking',
                    'Transmitter: line alignment',
                    'Transmitter: open/short test 10 kΩ',
                    'Transmitter: polarity test',
                    'Transmitter operating time: lebih dari 8 jam',
                    'Receiver wire tracking RJ11: hingga 3000 m',
                    'Receiver wire tracking RJ45: hingga 100 m',
                    'Receiver: NCV detection',
                    'Receiver: headset jack 3.5 mm',
                    'Receiver: low battery indicator',
                    'Receiver operating time: lebih dari 5 jam',
                ]),
                'description' => 'Nama alat pada katalog tercantum ganda sebagai Lux Meter. Nama data disesuaikan berdasarkan spesifikasi transmitter dan receiver.',
            ],
            [
                'category_slug' => 'alat-ukur',
                'name' => 'Humidity Meter',
                'brand' => 'UNI-T',
                'model' => 'UT333',
                'technical_specifications' => $specification([
                    'Temperature range: -10°C hingga 60°C',
                    'Temperature range: 14°F hingga 140°F',
                    'Temperature accuracy: ±1.0°C / ±2.0°F',
                    'Temperature resolution: 0.1°C / 0.2°F',
                    'Humidity range: 0% RH hingga 100% RH',
                    'Humidity accuracy: ±5% RH',
                    'Humidity resolution: 0.1% RH',
                    'Sampling rate: 1 kali per detik',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'Digital Power Meter',
                'brand' => 'Keweisi',
                'model' => 'KWS-AC300-100A',
                'technical_specifications' => $specification([
                    'Type: Close Mutual Inductance Sensor',
                    'Current range: 0 hingga 100 A AC',
                    'Input voltage: 50 hingga 300 V',
                    'Electricity measurement: 0.01 hingga 19999 kWh',
                    'Power measurement: 0 hingga 3 kW',
                    'Timing: 0 hingga 200 jam',
                ]),
            ],
            [
                'category_slug' => 'alat-elektrik',
                'name' => 'High Voltage Tester',
                'brand' => 'Kikusui',
                'model' => 'TOS9311',
                'technical_specifications' => $specification([
                    'AC output range: 0.050 kV hingga 10.000 kV',
                    'Output voltage waveform: sine',
                    'Frequency: 50 Hz / 60 Hz',
                    'Output range: 0.100 kV hingga 10.000 kV',
                    'Short circuit current: 50 mA',
                ]),
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Simpan data
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($facilities, $categoryIds) {
            foreach ($facilities as $facilityData) {
                $categorySlug = $facilityData['category_slug'];

                unset($facilityData['category_slug']);

                $identity = [
                    'name' => $facilityData['name'],
                    'brand' => $facilityData['brand'],
                    'model' => $facilityData['model'],
                ];

                $facilityData['category_id'] = $categoryIds->get($categorySlug);

                QcFacility::query()->updateOrCreate(
                    $identity,
                    $facilityData
                );
            }
        });
    }
}
