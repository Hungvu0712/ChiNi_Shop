
document.addEventListener("DOMContentLoaded", function () {
    const ghnToken = "18f28540-8fbc-11ef-839a-16ebf09470c6"; // ✅ Thay bằng token thật

    const provinceEl = document.getElementById("province");
    const districtEl = document.getElementById("district");
    const wardEl = document.getElementById("ward");
    const fullAddressEl = document.getElementById("fullAddress");

    let selectedProvince = "";
    let selectedDistrict = "";
    let selectedWard = "";

    // Load tỉnh/thành
    fetch("https://online-gateway.ghn.vn/shiip/public-api/master-data/province", {
        method: "GET",
        headers: { "Token": ghnToken }
    })
    .then(res => res.json())
    .then(data => {
        data.data.forEach(item => {
            const opt = document.createElement("option");
            opt.value = item.ProvinceID;
            opt.text = item.ProvinceName;
            provinceEl.appendChild(opt);
        });
    });

    // Khi chọn tỉnh -> load quận
    provinceEl.addEventListener("change", function () {
        const provinceId = this.value;
        selectedProvince = this.options[this.selectedIndex].text;

        // Reset
        districtEl.innerHTML = '<option disabled selected>Quận/Huyện</option>';
        wardEl.innerHTML = '<option disabled selected>Phường/Xã</option>';
        wardEl.disabled = true;

        fetch("https://online-gateway.ghn.vn/shiip/public-api/master-data/district", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Token": ghnToken
            },
            body: JSON.stringify({ province_id: parseInt(provinceId) })
        })
        .then(res => res.json())
        .then(data => {
            districtEl.disabled = false;
            data.data.forEach(item => {
                const opt = document.createElement("option");
                opt.value = item.DistrictID;
                opt.text = item.DistrictName;
                districtEl.appendChild(opt);
            });
        });
    });

    // Khi chọn quận -> load phường
    districtEl.addEventListener("change", function () {
        const districtId = this.value;
        selectedDistrict = this.options[this.selectedIndex].text;

        wardEl.innerHTML = '<option disabled selected>Phường/Xã</option>';
        wardEl.disabled = true;

        fetch("https://online-gateway.ghn.vn/shiip/public-api/master-data/ward", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Token": ghnToken
            },
            body: JSON.stringify({ district_id: parseInt(districtId) })
        })
        .then(res => res.json())
        .then(data => {
            wardEl.disabled = false;
            data.data.forEach(item => {
                const opt = document.createElement("option");
                opt.value = item.WardCode; // Dùng WardCode để gửi lên GHN
                opt.text = item.WardName;
                wardEl.appendChild(opt);
            });
        });
    });

    // Khi chọn phường -> ghép địa chỉ đầy đủ
    wardEl.addEventListener("change", function () {
        selectedWard = this.options[this.selectedIndex].text;

        const full = `${selectedWard} - ${selectedDistrict} - ${selectedProvince}`;
        fullAddressEl.value = full;
        document.getElementById("to_district_id").value = districtEl.value;
        document.getElementById("to_ward_code").value = wardEl.value;
    });
});

