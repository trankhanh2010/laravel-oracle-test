<?php

return [
    // Khoa phòng - Department
    'department' => [
        'department' => 'Khoa phòng',
        'department_id' => 'Id khoa phòng',
        'department_name' => 'Tên khoa phòng',
        'department_code' => 'Mã khoa phòng',
        'default_instr_patient_type_id' => 'Id đối tượng thanh toán mặc định khi chỉ định dịch vụ CLS',
        'theory_patient_count' => 'Số giường kế hoạch',
        'reality_patient_count' => 'Số giường thực tế',
        'req_surg_treatment_type_id' => 'Id diện điều trị được dùng khi tính công phẫu thuật thủ thuật đối với khoa chỉ định dịch vụ',
        'phone' => 'Số điện thoại',
        'head_loginname' => 'Loginname của trưởng khoa',
        'head_username' => 'Username của trưởng khoa',
        'is_exam' => 'Trường là khoa khám bệnh',
        'is_clinical' => 'Trường là khoa lâm sàng',
        'allow_assign_package_price' => 'Trường cho phép nhập giá gói lúc chỉ định gói',
        'auto_bed_assign_option' => 'Trường tự động cảnh báo và cho phép chỉ định giường, dịch vụ giường khi chuyển khoa, kết thúc điều trị',
        'is_emergency' => 'Trường là khoa cấp cứu',
        'is_auto_receive_patient' => 'Trường tự động tiếp nhận bệnh nhân vào khoa',
        'allow_assign_surgery_price' => 'Trường cho phép nhập giá lúc chỉ định phẫu thuật',
        'is_in_dep_stock_moba' => 'Trường mặc định chọn kho thu hồi là kho thuộc khoa',
        'warning_when_is_no_surg' => 'Trường cảnh báo khi chưa chỉ định dịch vụ phẫu thuật',
        'allow_treatment_type_ids' => 'Danh sách id diện điều trị',
        'accepted_icd_codes' => 'Danh sách icd code diện điều trị',
        'g_code' => 'Mã đơn vị',
        'bhyt_code' => 'Mã BHYT',
        'branch_id' => 'Id chi nhánh',
    ],
    // Khu vực - Area
    'area' => [
        'department_id' => 'Id khoa phòng',
        'area_name' => 'Tên khu vực',
        'area_code' => 'Mã khu vực',
    ],
    // Buồng bệnh - Bed Room
    'bed_room' => [
        'bed_room_code' => 'Mã buồng bệnh',
        'bed_room_name' => 'Tên buồng bệnh',
        'department_id' => 'Id khoa phòng',
        'area_id' => 'Id khu vực',
        'speciality_id' => 'Id chuyên khoa',
        'treatment_type_ids' => 'Danh sách diện điều trị',
        'default_cashier_room_id' => 'Id phòng thu ngân',
        'default_instr_patient_type_id' => 'Id đối tượng thanh toán mặc định khi chỉ định dịch vụ CLS',
        'is_surgery' => 'Trường là buồng phẫu thuật',
        'is_restrict_req_service' => 'Trường giới hạn dịch vụ chỉ định',
        'is_pause' => 'Trường tạm dừng',
        'is_restrict_execute_room' => 'Trường kiểm soát sử dụng phòng xử lý theo dịch vụ',
        'room_type_id' => 'Id loại phòng',
    ],
    // Phòng khám
    'execute_room' => [
        'execute_room_code' => 'Mã phòng khám',
        'execute_room_name' => 'Tên phòng khám',
        'department_id' => 'Id khoa phòng',
        'room_group_id' => 'Id nhóm phòng',
        'room_type_id' => 'Id loại phòng',
        'order_issue_code' => 'Mã sinh STT',
        'num_order' => 'STT',
        'test_type_code' => 'Mã loại xét nghiệm',
        'max_request_by_day' => 'Số lượt xử lý tối đa / ngày',
        'max_appointment_by_day' => 'Số lượt hẹn khám tối đa / ngày',
        'hold_order' => 'STT ưu tiên',
        'speciality_id' => 'Id chuyên khoa',
        'address' => 'Địa chỉ',
        'max_req_bhyt_by_day' => 'Số lượt BHYT thực hiện tối đa / ngày',
        'max_patient_by_day' => 'Số bệnh nhân xử lý tối đa / ngày',
        'average_eta' => 'Thời gian trung bình thực hiện một yêu cầu',
        'responsible_loginname' => 'Loginname bác sĩ phụ trách phòng',
        'responsible_username' => 'Username bác sĩ phụ trách phòng',
        'default_instr_patient_type_id' => 'Id đối tượng thanh toán mặc định',
        'default_drug_store_ids' => 'Danh sách id nhà thuốc mặc định',
        'default_cashier_room_id' => 'Id phòng thu ngân mặc định',
        'area_id' => 'Id khu vực',
        'screen_saver_module_link' => 'Link màn hình chờ',
        'bhyt_code' => 'Mã BHYT',
        'deposit_account_book_id' => 'Id sổ tạm ứng',
        'bill_account_book_id' => 'Id sổ thanh toán',
        'is_emergency' => 'Trường là phòng cấp cứu',
        'is_exam' => 'Trường là phòng khám',
        'is_speciality' => 'Trường là phòng chuyên khoa',
        'is_use_kiosk' => 'Trường là phòng KIOSK',
        'is_restrict_execute_room' => 'Trường giới hạn chỉ định phòng thực hiện',
        'is_restrict_time' => 'Trường giới hạn thời gian hoạt động',
        'is_vaccine' => 'Trường là phòng khám sàng lọc tiêm chủng',
        'is_restrict_req_service' => 'Trường kiểm soát dịch vụ chỉ định',
        'allow_not_choose_service' => 'Trường không bắt buộc chọn dịch vụ khi chỉ định công khám',
        'is_kidney' => 'Trường là phòng chạy thận',
        'kidney_shift_count' => 'Số ca chạy thận trong ngày',
        'is_surgery' => 'Trường là phòng mổ',
        'is_auto_expend_add_exam' => 'Tự động kiểm tra hao phí đối với các chỉ định khám thêm tại phòng này',
        'is_allow_no_icd' => 'Trường không cho phép nhập ICD',
        'is_pause' => 'Trường tạm dừng',
        'is_restrict_medicine_type' => 'Trường giới hạn sử dụng thuốc',
        'is_pause_enclitic' => 'Trường tạm ngừng tiếp nhận thêm yêu cầu xử lý',
        'is_vitamin_a' => 'Trường là phòng cho uống Vitamin A',
        'is_restrict_patient_type' => 'Trường giới hạn đối tượng bệnh nhân',
        'is_block_num_order' => 'Trường cấp số thứ tự theo khung thời gian',
        'default_service_id' => 'Id dịch vụ mặc định',
    ],
    // Nhóm phòng
    'room_group' => [
        'group_code' => 'Mã đơn vị',
        'room_group_name' => 'Tên nhóm phòng',
        'room_group_code' => 'Mã nhóm phòng',
    ],
    // Chuyên khoa
    'speciality' => [
        'speciality_code' => 'Mã chuyên khoa',
        'speciality_name' => 'Tên chuyên khoa',
        'bhyt_limit' => 'Giới hạn chi phí BHYT chi trả theo chuyên khoa'
    ],
];