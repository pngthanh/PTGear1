<?php
class Product
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // lấy sp cho fs ramdom 8sp
    public function getFlashSaleProducts($limit = 8)
    {
        $sql = "SELECT * FROM products ORDER BY RAND() LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    // lấy sp lọc theo category, subcategory và sắp xếp
    public function getProductsFiltered($categoryId, $filterSub = 0, $sort = "")
    {
        $orderBy = "ORDER BY created_at DESC";
        switch ($sort) {
            case "price_asc":
                $orderBy = "ORDER BY price ASC";
                break;
            case "price_desc":
                $orderBy = "ORDER BY price DESC";
                break;
            case "name":
                $orderBy = "ORDER BY name ASC";
                break;
        }

        $where = "WHERE category_id = ?";
        $params = [$categoryId];
        $types = "i";

        if ($filterSub > 0) {
            $where .= " AND subcategory_id = ?";
            $params[] = $filterSub;
            $types .= "i";
        }

        $sql = "SELECT * FROM products $where $orderBy";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }

    // lấy category
    public function getCategoryName($categoryId)
    {
        $stmt = $this->conn->prepare("SELECT name FROM categories WHERE id = ?");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // lấy subcategories
    public function getSubcategories($categoryId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM subcategories WHERE category_id = ?");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // chi tiết 1 sp
    public function getProductById($productId)
    {
        $sql = "SELECT p.*, c.name AS category_name, s.name AS subcategory_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN subcategories s ON p.subcategory_id = s.id 
                WHERE p.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    // 8 sp mới
    public function getNewestProducts($limit = 8)
    {
        $sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    // 8 sp bán chạy
    public function getBestSellingProducts($limit = 8)
    {
        // t chưa làm db phần đã bán nên t lấy cái sp sl dưới 30
        $sql = "SELECT * FROM products WHERE stock < 30 ORDER BY stock ASC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    // lấy 8 sp linh kiện/phụ kiện
    public function getFeaturedProducts($categoryId, $limit = 8)
    {
        $sql = "SELECT * FROM products WHERE category_id = ? ORDER BY RAND() LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $categoryId, $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    // lấy số liệu
    public function getDashboardStats()
    {
        $stats = [
            'todayRevenue' => 0,
            'todayOrders' => 0,
            'newCustomers' => 0,
            'totalStock' => 0
        ];

        // Doanh thu hôm nay (chỉ tính đơn "đã giao")
        $sqlRevenue = "SELECT SUM(total_price) as revenue FROM orders WHERE DATE(created_at) = CURDATE() AND status = 'completed'";
        $result = $this->conn->query($sqlRevenue);
        if ($result && $result->num_rows > 0) {
            $stats['todayRevenue'] = $result->fetch_assoc()['revenue'] ?? 0;
        }

        // Đơn hàng hôm nay (tất cả trạng thái)
        $sqlOrders = "SELECT COUNT(id) as orders FROM orders WHERE DATE(created_at) = CURDATE()";
        $result = $this->conn->query($sqlOrders);
        if ($result) $stats['todayOrders'] = $result->fetch_assoc()['orders'] ?? 0;

        // Khách hàng mới hôm nay
        $sqlCustomers = "SELECT COUNT(id) as customers FROM users WHERE DATE(created_at) = CURDATE() AND role = 'user'";
        $result = $this->conn->query($sqlCustomers);
        if ($result) $stats['newCustomers'] = $result->fetch_assoc()['customers'] ?? 0;

        // Tổng sản phẩm tồn kho
        $sqlStock = "SELECT SUM(stock) as stock FROM products";
        $result = $this->conn->query($sqlStock);
        if ($result) $stats['totalStock'] = $result->fetch_assoc()['stock'] ?? 0;

        return $stats;
    }

    // lấy danh thu 7 ngày gần nhất
    public function getRevenueLast7Days()
    {
        $sql = "SELECT 
                    DATE(created_at) as order_date, 
                    SUM(total_price) as daily_revenue
                FROM orders
                WHERE 
                    created_at >= CURDATE() - INTERVAL 7 DAY
                    AND status = 'completed'
                GROUP BY order_date
                ORDER BY order_date ASC";

        $result = $this->conn->query($sql);
        $revenueData = [];
        while ($row = $result->fetch_assoc()) {
            $revenueData[] = $row;
        }
        return $revenueData;
    }

    // lấy 5 đơn mới nhất
    public function getRecentOrders()
    {
        $sql = "SELECT o.id, u.fullname, o.total_price, o.status 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT 5";
        return $this->conn->query($sql);
    }

    // lấy sp đã bán
    public function getCategorySalesStructure()
    {
        $sql = "SELECT 
                    c.name as category_name, 
                    COUNT(od.product_id) as items_sold
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                JOIN categories c ON p.category_id = c.id
                JOIN orders o ON od.order_id = o.id
                WHERE o.status = 'completed'
                GROUP BY c.name";

        $result = $this->conn->query($sql);
        $structureData = [];
        while ($row = $result->fetch_assoc()) {
            $structureData[] = $row;
        }
        return $structureData;
    }

    //Lấy 3 sản phẩm bán chạy nhất
    public function getBestSellingProductsDashboard()
    {
        $sql = "SELECT 
                    p.name as product_name, 
                    SUM(od.quantity) as total_sold
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                JOIN orders o ON od.order_id = o.id
                WHERE o.status = 'completed'
                GROUP BY p.name
                ORDER BY total_sold DESC
                LIMIT 3";
        return $this->conn->query($sql);
    }
    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->conn->query($sql);
    }

    /**
     * Lấy tất cả danh mục con
     */
    public function getAllSubcategories()
    {
        $sql = "SELECT * FROM subcategories ORDER BY name ASC";
        return $this->conn->query($sql);
    }

    /**
     * Lấy danh mục con DỰA THEO danh mục chính
     */
    public function getSubcategoriesByCategoryId($categoryId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM subcategories WHERE category_id = ? ORDER BY name ASC");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Lấy sản phẩm cho trang Admin
     */
    public function getAdminProducts($categoryId = 0, $subcategoryId = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, s.name as subcategory_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN subcategories s ON p.subcategory_id = s.id";

        $params = [];
        $types = "";

        if ($categoryId > 0) {
            $sql .= " WHERE p.category_id = ?";
            $params[] = $categoryId;
            $types .= "i";
        }

        if ($subcategoryId > 0) {
            $sql .= ($categoryId > 0 ? " AND" : " WHERE") . " p.subcategory_id = ?";
            $params[] = $subcategoryId;
            $types .= "i";
        }

        $sql .= " ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Thêm sản phẩm mới
     */
    public function createProduct($data, $file)
    {
        // 1. Xử lý file upload
        $imagePathInDb = null;
        if (isset($file) && $file['error'] == 0) {
            $targetDir = __DIR__ . "/../assets/img/sanpham/";

            // Đảm bảo thư mục tồn tại
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = uniqid() . '-' . basename($file['name']);
            $targetFile = $targetDir . $fileName;

            // Di chuyển file
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {

                $imagePathInDb = 'img/sanpham/' . $fileName;
            } else {
                return "Lỗi khi tải ảnh lên.";
            }
        } else {
            return "Vui lòng chọn ảnh sản phẩm.";
        }

        // 2. Insert vào CSDL
        $stmt = $this->conn->prepare("INSERT INTO products (category_id, subcategory_id, name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iisssis",
            $data['category_id'],
            $data['subcategory_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $imagePathInDb
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return "Lỗi CSDL: " . $stmt->error;
        }
    }

    /**
     * Cập nhật sản phẩm
     */
    public function updateProduct($data, $file)
    {
        $imageSql = "";
        $types = "iisssi";
        $params = [
            $data['category_id'],
            $data['subcategory_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock']
        ];

        // 1. Xử lý file 
        if (isset($file) && $file['error'] == 0) {
            $targetDir = __DIR__ . "/../assets/img/sanpham/";
            $fileName = uniqid() . '-' . basename($file['name']);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $imagePathInDb = 'img/sanpham/' . $fileName;
                $imageSql = ", image = ?";
                $types .= "s";
                $params[] = $imagePathInDb;
            } else {
                return "Lỗi khi tải ảnh mới lên.";
            }
        }

        // 2. Update CSDL
        $params[] = $data['id'];
        $types .= "i";

        $stmt = $this->conn->prepare("UPDATE products SET 
            category_id = ?, 
            subcategory_id = ?, 
            name = ?, 
            description = ?, 
            price = ?, 
            stock = ?
            $imageSql 
            WHERE id = ?");

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            return true;
        } else {
            return "Lỗi CSDL: " . $stmt->error;
        }
    }

    /**
     * Xóa sản phẩm
     */
    public function deleteProduct($id)
    {

        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Thêm danh mục con mới
     */
    public function createSubcategory($categoryId, $name)
    {
        $stmt = $this->conn->prepare("INSERT INTO subcategories (category_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $categoryId, $name);
        return $stmt->execute();
    }

    /**
     * Sửa tên danh mục con
     */
    public function updateSubcategory($subcategoryId, $name)
    {
        $stmt = $this->conn->prepare("UPDATE subcategories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $subcategoryId);
        return $stmt->execute();
    }
}
