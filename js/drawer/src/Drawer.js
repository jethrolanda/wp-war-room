import { useState, useEffect } from "react";
import { Button, Drawer, DatePicker, Space } from "antd";
import dayjs from "dayjs";
const { RangePicker } = DatePicker;

const rangePresets = [
  { label: "Last 7 Days", value: [dayjs().add(-7, "d"), dayjs()] },
  { label: "Last 14 Days", value: [dayjs().add(-14, "d"), dayjs()] },
  { label: "Last 30 Days", value: [dayjs().add(-30, "d"), dayjs()] },
  { label: "Last 90 Days", value: [dayjs().add(-90, "d"), dayjs()] }
];
const onRangeChange = (dates, dateStrings) => {
  if (dates) {
    document.getElementById("date-start").value = dateStrings[0];
    document.getElementById("date-end").value = dateStrings[1];
  }
};

const onRangeCompareChange = (dates, dateStrings) => {
  if (dates) {
    document.getElementById("compare-start").value = dateStrings[0];
    document.getElementById("compare-end").value = dateStrings[1];
  }
};

const App = () => {
  const [open, setOpen] = useState(false);
  const [placement, setPlacement] = useState("right");
  const showDrawer = () => {
    setOpen(true);
  };
  const onClose = () => {
    setOpen(false);
  };

  const onOk = () => {
    const form = document.getElementById("date-filter");
    form.requestSubmit();
    setOpen(false);
  };

  useEffect(() => {
    document.getElementById("date-start").value = dayjs()
      .add(-7, "d")
      .format("YYYY-MM-DD");
    document.getElementById("date-end").value = dayjs().format("YYYY-MM-DD");
  }, []);

  return (
    <>
      <Button
        onClick={showDrawer}
        className="block w-full font-bold text-lg !bg-[#bbbcba] !hover:bg-[#bbbcba] !text-black !border-[#bbbcba] rounded-none"
      >
        YESTERDAY / 7 Days / Month / Quarter / Custom
      </Button>
      <Drawer
        title="Report date range"
        placement={placement}
        width={500}
        onClose={onClose}
        open={open}
        extra={
          <Space>
            <Button onClick={onClose}>Cancel</Button>
            <Button type="primary" onClick={onOk}>
              OK
            </Button>
          </Space>
        }
      >
        <Space direction="vertical">
          Date Range
          <RangePicker
            size="large"
            defaultValue={[dayjs().add(-7, "d"), dayjs()]}
            presets={[
              {
                label: (
                  <span aria-label="Current Time to End of Day">Now ~ EOD</span>
                ),
                value: () => [dayjs(), dayjs().endOf("day")] // 5.8.0+ support function
              },
              ...rangePresets
            ]}
            format="YYYY-MM-DD"
            onChange={onRangeChange}
          />
          Compare date range
          <RangePicker
            size="large"
            defaultValue={[dayjs().add(-14, "d"), dayjs().add(-7, "d")]}
            presets={[
              {
                label: (
                  <span aria-label="Current Time to End of Day">Now ~ EOD</span>
                ),
                value: () => [dayjs(), dayjs().endOf("day")] // 5.8.0+ support function
              },
              ...rangePresets
            ]}
            format="YYYY-MM-DD"
            onChange={onRangeCompareChange}
          />
        </Space>
      </Drawer>
    </>
  );
};
export default App;
