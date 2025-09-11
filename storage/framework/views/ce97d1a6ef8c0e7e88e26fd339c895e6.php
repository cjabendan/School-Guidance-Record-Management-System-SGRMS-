<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #2563eb;
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            font-size: 10.5px; 
            table-layout: fixed;
        }
        th, td {
            word-break: break-word;
            max-width: 120px;
        }
        @page {
            size: A4 landscape;
            margin: 20px 25px 20px 25px;
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 6px 8px; 
            text-align: left;
        }
        th { 
            background: #2563eb; 
            color: #fff; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) { 
            background: #f9f9f9; 
        }
        tr:hover {
            background: #eef4ff;
        }
    </style>
</head>
<body>
    <h2>
        <?php
            $filterValue = isset($filter) ? strtolower($filter) : '';
            $listTitle = '';
            if ($filterValue === 'elementary') {
                $listTitle = 'Elementary List';
            } elseif ($filterValue === 'juniorhigh') {
                $listTitle = 'Junior High School List';
            } elseif ($filterValue === 'seniorhigh') {
                $listTitle = 'Senior High School List';
            } elseif ($filterValue === 'kindergarten') {
                $listTitle = 'Kindergarten List';
            } elseif ($filterValue === 'inactive') {
                $listTitle = 'Inactive Students List';
            }
        ?>
        <?php echo e($listTitle ?: 'Student List'); ?>

    </h2>
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Year Level</th>
                <th>Section</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Contact Number</th>
                <th>Email Address</th>
                <th>Address</th>
                <th>Father's Name</th>
                <th>Mother's Name</th>
                <th>Guardian's Name</th>
                <th>Relationship</th>
                <th>Guardian Contact</th>
                <th>Guardian Email</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($student->s_id ?? ''); ?></td>
                    <td><?php echo e(trim(($student->lname ?? '') . ', ' . ($student->fname ?? '') . ' ' . ($student->mname ?? '') . ' ' . ($student->suffix ?? ''))); ?></td>
                    <td><?php echo e($student->year_level ?? ''); ?></td>
                    <td><?php echo e($student->section ?? ''); ?></td>
                    <td><?php echo e($student->sex ?? ''); ?></td>
                    <td><?php echo e($student->bod ? date('F d, Y', strtotime($student->bod)) : ''); ?></td>
                    <td><?php echo e($student->contact_num ?? ''); ?></td>
                    <td><?php echo e($student->email ?? ''); ?></td>
                    <td><?php echo e($student->address ?? ''); ?></td>
                    <td><?php echo e($student->father_name ?? ''); ?></td>
                    <td><?php echo e($student->mother_name ?? ''); ?></td>
                    <td><?php echo e($student->guardian_name ?? ''); ?></td>
                    <td><?php echo e($student->relationship ?? ''); ?></td>
                    <td><?php echo e($student->guardian_contact ?? ''); ?></td>
                    <td><?php echo e($student->guardian_email ?? ''); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\Head\profiling\export_pdf.blade.php ENDPATH**/ ?>